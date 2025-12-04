#!/usr/bin/env python3
# build_quran_full.py
# Usage: python3 build_quran_full.py
# Output: quran_full.json, quran_full.sql

import re
import json
from pathlib import Path
from datetime import datetime

SQL_PATH = Path("quran-uthmani.sql")
META_PATH = Path("quran_mushaf_meta.json")
OUT_JSON = Path("quran_full.json")
OUT_SQL = Path("quran_full.sql")

def load_meta():
    if not META_PATH.exists():
        print(f"ERROR: meta file not found: {META_PATH}")
        return {}
    data = json.loads(META_PATH.read_text(encoding='utf-8'))
    lookup = {}
    for item in data:
        try:
            s = int(item.get('surah'))
            a = int(item.get('ayah'))
            lookup[(s,a)] = {
                'page': item.get('page'),
                'juz': item.get('juz'),
                'hizb': item.get('hizb'),
                'quarter': item.get('quarter')
            }
        except Exception:
            continue
    return lookup

def extract_from_sql(sql_text):
    verses = {}
    # Strategy A: pattern (id, surah, ayah, 'text' ...)
    patternA = re.compile(
        r"\(\s*\d+\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*'((?:[^'\\]|\\.)*)'",
        re.M
    )
    for m in patternA.finditer(sql_text):
        s = int(m.group(1)); a = int(m.group(2)); txt = m.group(3)
        txt = txt.replace("''", "'").replace("\\n", "\n").replace("\\'", "'")
        verses[(s,a)] = txt

    # Strategy B: pattern (surah, ayah, 'text' ...)
    patternB = re.compile(
        r"\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*'((?:[^'\\]|\\.)*)'",
        re.M
    )
    for m in patternB.finditer(sql_text):
        s = int(m.group(1)); a = int(m.group(2)); txt = m.group(3)
        txt = txt.replace("''", "'").replace("\\n", "\n").replace("\\'", "'")
        verses.setdefault((s,a), txt)

    # Strategy C: look for VALUES blocks and split safely
    if len(verses) < 6000:
        mblock = re.search(r"INSERT\s+INTO.*?VALUES\s*(.+);", sql_text, flags=re.S|re.I)
        if mblock:
            block = mblock.group(1)
            # split on ),( but keep grouping
            parts = re.split(r"\),\s*\(", block)
            for p in parts:
                p = p.strip().lstrip("(").rstrip(")")
                # split fields but ignore commas inside quotes
                fields = re.split(r",\s*(?=(?:[^']*'[^']*')*[^']*$)", p)
                # try to find two small integers and one quoted string
                nums = []
                text = None
                for f in fields:
                    f = f.strip()
                    if f.startswith("'") and f.endswith("'"):
                        text = f[1:-1]
                    else:
                        try:
                            n = int(f)
                            nums.append(n)
                        except:
                            pass
                if text and len(nums) >= 2:
                    # find candidate surah,ayah among nums (<=114, verse <= 286)
                    cand = [n for n in nums if n <= 114]
                    if len(cand) >= 2:
                        s, a = cand[0], cand[1]
                    else:
                        s, a = nums[0], nums[1]
                    text = text.replace("''", "'").replace("\\n", "\n").replace("\\'", "'")
                    verses.setdefault((int(s),int(a)), text)

    return verses

def build_outputs(verses_map, meta_lookup):
    items = []
    for (s,a) in sorted(verses_map.keys(), key=lambda x: (x[0], x[1])):
        txt = verses_map[(s,a)]
        meta = meta_lookup.get((s,a), {})
        items.append({
            "surah": s,
            "ayah": a,
            "text": txt,
            "page": meta.get('page'),
            "juz": meta.get('juz'),
            "hizb": meta.get('hizb'),
            "quarter": meta.get('quarter')
        })
    # write JSON
    OUT_JSON.write_text(json.dumps(items, ensure_ascii=False, indent=2), encoding='utf-8')
    # write SQL INSERT
    now = datetime.utcnow().strftime("%Y-%m-%d %H:%M:%S")
    with OUT_SQL.open("w", encoding='utf-8') as f:
        f.write("INSERT INTO verses (surah_id, verse_number, text_ar, page_number, juz_number, hizb_number, hizb_quarter, created_at, updated_at) VALUES\n")
        rows = []
        for it in items:
            txt_sql = it['text'].replace("'", "''")
            page = str(it['page']) if it['page'] is not None else "NULL"
            juz = str(it['juz']) if it['juz'] is not None else "NULL"
            hizb = str(it['hizb']) if it['hizb'] is not None else "NULL"
            quarter = str(it['quarter']) if it['quarter'] is not None else "NULL"
            rows.append(f"({it['surah']},{it['ayah']},'{txt_sql}',{page},{juz},{hizb},{quarter},'{now}','{now}')")
        f.write(",\n".join(rows))
        f.write(";\n")
    return len(items)

def main():
    if not SQL_PATH.exists():
        print("ERROR: quran-uthmani.sql not found here.")
        return
    if not META_PATH.exists():
        print("WARNING: meta file not found -> will still produce JSON with no meta.")
    meta_lookup = load_meta()
    sql_text = SQL_PATH.read_text(encoding='utf-8', errors='ignore')
    verses_map = extract_from_sql(sql_text)
    if not verses_map:
        print("ERROR: لم يتم استخراج أي آيات من ملف SQL. تأكد من أن الملف يحتوي INSERT/VALUES لآيات.")
        return
    count = build_outputs(verses_map, meta_lookup)
    print(f"Done. Extracted {count} verses. Files written: {OUT_JSON}, {OUT_SQL}")

if __name__ == "__main__":
    main()
