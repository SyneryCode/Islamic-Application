import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'core/theme/app_theme.dart';
import 'features/auth/presentation/providers/welcome_provider.dart';
import 'features/auth/presentation/screens/welcome_screen.dart';
import 'package:islamic_app_frontend/features/auth/presentation/providers/register_provider.dart';
import 'features/auth/presentation/providers/login_provider.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => WelcomeProvider()),
        ChangeNotifierProvider(create: (_) => RegisterProvider()),
        ChangeNotifierProvider(create: (_) => LoginProvider()),
      ],
      child: MaterialApp(
        debugShowCheckedModeBanner: false,
        title: 'التطبيق الإسلامي',
        theme: AppTheme.lightTheme,
        home: const WelcomeScreen(),
      ),
    );
  }
}
