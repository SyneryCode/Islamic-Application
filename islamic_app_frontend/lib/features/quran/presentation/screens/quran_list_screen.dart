import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/quran_provider.dart';
import '../../domain/models/surah.dart';
import 'surah_screen.dart';

class QuranListScreen extends StatelessWidget {
  const QuranListScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider.value(
      value: Provider.of<QuranProvider>(context),
      child: const _QuranListBody(),
    );
  }
}

class _QuranListBody extends StatelessWidget {
  const _QuranListBody();

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<QuranProvider>(context);

    // تحميل القائمة عند فتح الشاشة لأول مرة
    if (provider.surahList.isEmpty && !provider.isLoadingSurahs) {
      WidgetsBinding.instance.addPostFrameCallback((_) {
        provider.loadSurahList();
      });
    }

    return Directionality(
      textDirection: TextDirection.rtl,
      child: Scaffold(
        appBar: AppBar(
          title: const Text('القرآن الكريم'),
          centerTitle: true,
          backgroundColor: Colors.green[800],
        ),
        body:
            provider.isLoadingSurahs
                ? const Center(child: CircularProgressIndicator())
                : provider.error != null
                ? Center(child: Text('خطأ: ${provider.error}'))
                : ListView.builder(
                  itemCount: provider.surahList.length,
                  itemBuilder: (context, index) {
                    final surah = provider.surahList[index];
                    return _SurahTile(surah: surah);
                  },
                ),
      ),
    );
  }
}

class _SurahTile extends StatelessWidget {
  final Surah surah;

  const _SurahTile({required this.surah});

  @override
  Widget build(BuildContext context) {
    return ListTile(
      leading: CircleAvatar(
        backgroundColor: Colors.green[100],
        child: Text(
          surah.number.toString(),
          style: TextStyle(color: Colors.green[800]),
        ),
      ),
      title: Text(
        surah.name,
        style: const TextStyle(fontWeight: FontWeight.bold),
      ),
      subtitle: Text(
        '${surah.englishName} • ${surah.numberOfAyahs} آية',
        style: const TextStyle(fontSize: 13),
      ),
      trailing: Icon(Icons.arrow_forward_ios, size: 16, color: Colors.grey),
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(builder: (context) => SurahScreen(surah: surah)),
        );
      },
    );
  }
}
