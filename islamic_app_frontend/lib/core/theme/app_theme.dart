import 'package:flutter/material.dart';

class AppTheme {
  static const Color primary = Color(0xFF2E8B57);
  static final ThemeData lightTheme = ThemeData(
    scaffoldBackgroundColor: const Color(0xFFF5F8F5),
    primaryColor: primary,
    fontFamily: 'Roboto',
    textTheme: const TextTheme(
      headlineMedium: TextStyle(fontSize: 30, fontWeight: FontWeight.w700),
      titleLarge: TextStyle(fontSize: 18, fontWeight: FontWeight.w600),
      bodyMedium: TextStyle(fontSize: 16),
    ),

    elevatedButtonTheme: ElevatedButtonThemeData(
      style: ElevatedButton.styleFrom(
        backgroundColor: primary,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.all(Radius.circular(14)),
        ),
        minimumSize: const Size(double.infinity, 58),
        elevation: 2,
      ),
    ),
  );
}
