import 'dart:convert';
import 'package:http/http.dart' as http;
import '../domain/models/surah.dart';
import '../domain/models/ayah.dart';

class QuranApiService {
  static const String _baseUrl = 'https://api.alquran.cloud/v1';

  Future<List<Surah>> fetchSurahList() async {
    final response = await http.get(Uri.parse('$_baseUrl/meta'));
    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      final List<dynamic> surahsJson = data['data']['surahs']['references'];
      return surahsJson.map((s) => Surah.fromJson(s)).toList();
    } else {
      throw Exception('فشل تحميل قائمة السور');
    }
  }

  Future<List<Ayah>> fetchSurahAyahs(int surahNumber) async {
    try {
      final response = await http.get(
        Uri.parse('$_baseUrl/surah/$surahNumber/quran-simple'),
      );

      if (response.statusCode != 200) {
        throw Exception('HTTP Error: ${response.statusCode}');
      }

      final data = jsonDecode(response.body);

      if (data['data'] == null || data['data']['ayahs'] == null) {
        throw Exception('بيانات السورة غير متوفرة');
      }

      final List<dynamic> ayahsJson = data['data']['ayahs'];

      return ayahsJson.map((a) {
        if (a is! Map<String, dynamic>) {
          throw Exception('آية غير صالحة');
        }

        final number = a['number'] as int?;
        final text = a['text'] as String?;
        final numberInSurah = a['numberInSurah'] as int?;

        if (number == null || text == null || numberInSurah == null) {
          throw Exception('بيانات الآية ناقصة');
        }

        return Ayah.fromJson(a, surahNumber); // ✅ نمرر رقم السورة هنا
      }).toList();
    } catch (e) {
      throw Exception('فشل تحميل آيات السورة: $e');
    }
  }
}
