import 'package:flutter/foundation.dart';
import '../../data/quran_api_service.dart';
import '../../domain/models/surah.dart';
import '../../domain/models/ayah.dart';

class QuranProvider extends ChangeNotifier {
  final QuranApiService _api = QuranApiService();

  List<Surah> _surahList = [];
  bool _isLoadingSurahs = false;
  String? _error;

  List<Surah> get surahList => _surahList;
  bool get isLoadingSurahs => _isLoadingSurahs;
  String? get error => _error;

  Future<void> loadSurahList() async {
    if (_surahList.isNotEmpty) return; // تجنب التحميل المكرر
    _isLoadingSurahs = true;
    _error = null;
    notifyListeners();

    try {
      _surahList = await _api.fetchSurahList();
    } catch (e) {
      _error = e.toString();
    } finally {
      _isLoadingSurahs = false;
      notifyListeners();
    }
  }

  Future<List<Ayah>> loadSurahAyahs(int surahNumber) async {
    return await _api.fetchSurahAyahs(surahNumber);
  }
}
