class Ayah {
  final int number; // رقم الآية عالميًا
  final String text;
  final int surahNumber; // رقم السورة (نأخذه من خارج الآية)
  final int numberInSurah; // رقم الآية داخل السورة

  Ayah({
    required this.number,
    required this.text,
    required this.surahNumber,
    required this.numberInSurah,
  });

  factory Ayah.fromJson(Map<String, dynamic> json, int surahNumber) {
    return Ayah(
      number: json['number'] as int,
      text: json['text'] as String,
      surahNumber: surahNumber, // نأخذ الرقم من خارج الآية
      numberInSurah: json['numberInSurah'] as int,
    );
  }
}
