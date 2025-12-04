import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';

class HomePage extends StatelessWidget {
  const HomePage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.menu, color: Color(0xFFD4AF37)),
          onPressed: () {
            // TODO: Implement menu functionality
          },
        ),
        title: const Text(
          'New York',
          style: TextStyle(color: Colors.black, fontWeight: FontWeight.bold),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications, color: Colors.black),
            onPressed: () {
              // TODO: Implement notifications
            },
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Card for Date
            Container(
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16.0),
                boxShadow: [
                  BoxShadow(
                    color: Colors.grey.withOpacity(0.1),
                    spreadRadius: 2,
                    blurRadius: 5,
                    offset: const Offset(0, 3),
                  ),
                ],
              ),
              padding: const EdgeInsets.all(16.0),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        '25',
                        style: TextStyle(
                          fontSize: 32,
                          fontWeight: FontWeight.bold,
                          color: Colors.green[800],
                        ),
                      ),
                      const Text('March'),
                      const Text('2024'),
                    ],
                  ),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.end,
                    children: [
                      Text(
                        '١٥',
                        style: TextStyle(
                          fontSize: 32,
                          fontWeight: FontWeight.bold,
                          color: const Color(0xFFD4AF37),
                        ),
                      ),
                      const Text('رمضان'),
                      const Text('١٤٤٥'),
                    ],
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24.0),

            // Prayer Times Section
            const Text(
              'Prayer Times',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16.0),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                _buildPrayerCard(
                  icon: FontAwesomeIcons.sun,
                  name: 'الفجر',
                  time: '04:55 AM',
                  isNext: false,
                ),
                _buildPrayerCard(
                  icon: FontAwesomeIcons.sun,
                  name: 'الظهر',
                  time: '12:30 PM',
                  isNext: true,
                  nextTime: '45m',
                ),
                _buildPrayerCard(
                  icon: FontAwesomeIcons.sun,
                  name: 'العصر',
                  time: '04:05 PM',
                  isNext: false,
                ),
              ],
            ),
            const SizedBox(height: 24.0),

            // Qibla Direction Section
            const Text(
              'Qibla Direction',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16.0),
            Container(
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16.0),
                boxShadow: [
                  BoxShadow(
                    color: Colors.grey.withOpacity(0.1),
                    spreadRadius: 2,
                    blurRadius: 5,
                    offset: const Offset(0, 3),
                  ),
                ],
              ),
              padding: const EdgeInsets.all(16.0),
              child: Column(
                children: [
                  // داخل Column في Qibla Direction Card
                  SizedBox(
                    height: 300,
                    width: double.infinity,
                    child: Stack(
                      alignment: Alignment.center,
                      children: [
                        // Circle with directions
                        Container(
                          decoration: BoxDecoration(
                            shape: BoxShape.circle,
                            border: Border.all(
                              color: const Color(0xFFD4AF37),
                              width: 2,
                            ),
                          ),
                          child: Padding(
                            padding: const EdgeInsets.all(16.0),
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Text(
                                  'ش',
                                  style: TextStyle(
                                    fontSize: 24,
                                    color: Colors.green[800],
                                  ),
                                ),
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text(
                                      'غ',
                                      style: TextStyle(
                                        fontSize: 24,
                                        color: Colors.grey[600],
                                      ),
                                    ),
                                    Text(
                                      'ق',
                                      style: TextStyle(
                                        fontSize: 24,
                                        color: Colors.grey[600],
                                      ),
                                    ),
                                  ],
                                ),
                              ],
                            ),
                          ),
                        ),
                        // Kaaba Image
                        Container(
                          width: 60,
                          height: 60,
                          decoration: BoxDecoration(
                            shape: BoxShape.circle,
                            color: Colors.white,
                            boxShadow: [
                              BoxShadow(
                                color: Colors.yellow.withOpacity(0.3),
                                blurRadius: 10,
                                spreadRadius: 5,
                              ),
                            ],
                          ),
                          child: Center(
                            //child: Image.asset(
                            // 'assets/kaaba.png',
                            // width: 40,
                            // height: 40,
                          ),
                        ),
                        //),
                        // Pointer - ✅ تم تعديله ليكون مثلثًا حقيقيًا
                        Positioned(
                          bottom: 20,
                          right: 20,
                          child: _buildTriangle(125),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 16.0),
                  Column(
                    children: [
                      Text(
                        '125° SE',
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                          color: Colors.green[800],
                        ),
                      ),
                      const Text('2,450 km to Makkah'),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
      bottomNavigationBar: BottomNavigationBar(
        type: BottomNavigationBarType.fixed,
        selectedItemColor: Colors.white,
        unselectedItemColor: Colors.grey[600],
        currentIndex: 2, // Index of the central item
        onTap: (index) {
          // TODO: Handle navigation
        },
        items: [
          BottomNavigationBarItem(icon: const Icon(Icons.home), label: 'Home'),
          BottomNavigationBarItem(
            icon: const Icon(Icons.explore),
            label: 'Qibla',
          ),
          BottomNavigationBarItem(
            icon: GestureDetector(
              onTap: () {
                Navigator.pushNamed(context, '/quran');
              },
              child: Container(
                width: 50,
                height: 50,
                decoration: BoxDecoration(
                  color: Colors.green[800],
                  shape: BoxShape.circle,
                ),
                child: const Icon(Icons.menu_book, color: Colors.white),
              ),
            ),
            label: '', // نُبقيه فارغًا لأنه زر كبير
          ),
          BottomNavigationBarItem(
            icon: const Icon(Icons.notifications),
            label: 'Updates',
          ),
          BottomNavigationBarItem(
            icon: const Icon(Icons.person),
            label: 'Profile',
          ),
        ],
      ),
    );
  }

  Widget _buildPrayerCard({
    required IconData icon,
    required String name,
    required String time,
    bool isNext = false,
    String? nextTime,
  }) {
    return Container(
      width: 100,
      decoration: BoxDecoration(
        color: isNext ? Colors.green[800] : Colors.white,
        borderRadius: BorderRadius.circular(16.0),
        boxShadow:
            isNext
                ? [
                  BoxShadow(
                    color: Colors.grey.withOpacity(0.2),
                    spreadRadius: 2,
                    blurRadius: 5,
                    offset: const Offset(0, 3),
                  ),
                ]
                : null,
      ),
      padding: const EdgeInsets.all(16.0),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(icon, color: isNext ? Colors.white : Colors.grey[600], size: 24),
          const SizedBox(height: 8.0),
          Text(
            name,
            style: TextStyle(
              color: isNext ? Colors.white : Colors.black,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 8.0),
          Text(
            time,
            style: TextStyle(
              color: isNext ? Colors.white : Colors.black,
              fontWeight: FontWeight.bold,
            ),
          ),
          if (isNext && nextTime != null) ...[
            const SizedBox(height: 8.0),
            Text(
              'Next prayer in $nextTime',
              style: const TextStyle(color: Colors.white, fontSize: 12),
            ),
            const SizedBox(height: 8.0),
            LinearProgressIndicator(
              value: 0.5, // Example value, you can calculate this based on time
              backgroundColor: Colors.white.withOpacity(0.3),
              valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
            ),
          ],
        ],
      ),
    );
  }
}

Widget _buildTriangle(double angle) {
  return Transform.rotate(
    angle: angle * 3.14159 / 180, // تحويل الدرجات إلى راديان
    child: ClipPath(
      clipper: TriangleClipper(),
      child: Container(
        width: 30,
        height: 30,
        color: const Color(0xFFD4AF37), // اللون الذهبي
      ),
    ),
  );
}

class TriangleClipper extends CustomClipper<Path> {
  @override
  Path getClip(Size size) {
    final path = Path();
    path.moveTo(size.width / 2, 0); // قمة المثلث
    path.lineTo(size.width, size.height); // الركن الأيمن السفلي
    path.lineTo(0, size.height); // الركن الأيسر السفلي
    path.close();
    return path;
  }

  @override
  bool shouldReclip(covariant CustomClipper<Path> oldClipper) => false;
}
