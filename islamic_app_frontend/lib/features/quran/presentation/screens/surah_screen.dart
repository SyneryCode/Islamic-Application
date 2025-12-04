import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/quran_provider.dart';
import '../../domain/models/surah.dart';
import '../../domain/models/ayah.dart';

class SurahScreen extends StatefulWidget {
  final Surah surah;
  const SurahScreen({super.key, required this.surah});

  @override
  State<SurahScreen> createState() => _SurahScreenState();
}

class _SurahScreenState extends State<SurahScreen> {
  late Future<List<Ayah>> _ayahsFuture;

  @override
  void initState() {
    super.initState();
    final provider = Provider.of<QuranProvider>(context, listen: false);
    _ayahsFuture = provider.loadSurahAyahs(widget.surah.number);
  }

  @override
  Widget build(BuildContext context) {
    return Directionality(
      textDirection: TextDirection.rtl,
      child: Scaffold(
        appBar: AppBar(
          title: Text(widget.surah.name),
          centerTitle: true,
          backgroundColor: Colors.green[800],
        ),
        body: FutureBuilder<List<Ayah>>(
          future: _ayahsFuture,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const Center(child: CircularProgressIndicator());
            }
            if (snapshot.hasError) {
              return Center(child: Text('خطأ: ${snapshot.error}'));
            }
            final ayahs = snapshot.data!;
            return ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: ayahs.length,
              itemBuilder: (context, index) {
                final ayah = ayahs[index];
                return _AyahCard(number: ayah.numberInSurah, text: ayah.text);
              },
            );
          },
        ),
      ),
    );
  }
}

class _AyahCard extends StatelessWidget {
  final int number;
  final String text;

  const _AyahCard({required this.number, required this.text});

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.grey[50],
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: Colors.green.withOpacity(0.2)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            '﴿$text﴾',
            style: const TextStyle(fontSize: 18, height: 1.6),
            textAlign: TextAlign.justify,
          ),
          const SizedBox(height: 6),
          Align(
            alignment: Alignment.centerLeft,
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
              decoration: BoxDecoration(
                color: Colors.green[100],
                borderRadius: BorderRadius.circular(10),
              ),
              child: Text(
                number.toString(),
                style: TextStyle(
                  color: Colors.green[800],
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
