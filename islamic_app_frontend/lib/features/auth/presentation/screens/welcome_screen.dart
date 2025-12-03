import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/welcome_provider.dart';
import '../widgets/rounded_button.dart';

class WelcomeScreen extends StatelessWidget {
  const WelcomeScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<WelcomeProvider>(context);

    return Directionality(
      textDirection: TextDirection.rtl,
      child: Scaffold(
        body: SafeArea(
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 22.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                const SizedBox(height: 40),

                Column(
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: const [
                        Text(
                          'مرحبًا بك في تطبيقنا',
                          style: TextStyle(
                            fontSize: 28,
                            fontWeight: FontWeight.w700,
                          ),
                        ),
                        SizedBox(width: 8),
                        Text('🕌', style: TextStyle(fontSize: 28)),
                      ],
                    ),
                    const SizedBox(height: 12),
                    const Text(
                      'الإسلامي',
                      style: TextStyle(
                        fontSize: 26,
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                    const SizedBox(height: 10),
                    Text(
                      'ارتقِ بروحانيتك مع أدواتنا المتكاملة',
                      style: Theme.of(context).textTheme.bodyMedium!.copyWith(
                        color: Colors.green[700],
                      ),
                    ),
                  ],
                ),

                const Spacer(),

                // الأزرار
                if (provider.isLoading)
                  const Center(child: CircularProgressIndicator())
                else ...[
                  RoundedButton(
                    label: 'إنشاء حساب جديد',
                    onPressed: () => provider.createAccount(context),
                    filled: true,
                  ),
                  const SizedBox(height: 12),
                  RoundedButton(
                    label: 'تسجيل الدخول',
                    onPressed: () => provider.login(context),
                    filled: false,
                  ),
                  const SizedBox(height: 18),
                  TextButton(
                    onPressed: () => provider.guestLogin(context),
                    child: const Text(
                      'الدخول كزائر',
                      style: TextStyle(fontSize: 18, color: Colors.black),
                    ),
                  ),
                  const SizedBox(height: 18),
                  const Text(
                    '⚡ دخول سريع',
                    style: TextStyle(fontSize: 16, color: Colors.green),
                  ),
                  const SizedBox(height: 12),

                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                    children: [
                      _socialButton(
                        context,
                        'Facebook',
                        Icons.facebook,
                        () => provider.socialLogin('Facebook', context),
                      ),
                      _socialButton(
                        context,
                        'Apple',
                        Icons.apple,
                        () => provider.socialLogin('Apple', context),
                      ),
                      _socialButton(
                        context,
                        'Google',
                        Icons.g_mobiledata,
                        () => provider.socialLogin('Google', context),
                      ),
                    ],
                  ),
                ],

                const Spacer(),

                Padding(
                  padding: const EdgeInsets.only(bottom: 8.0),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: const [
                      Icon(Icons.mobile_friendly, size: 18),
                      SizedBox(width: 6),
                      Text(
                        'تطبيق موثوق • آمن • مصمم للمسلمين',
                        style: TextStyle(fontSize: 13),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _socialButton(
    BuildContext context,
    String label,
    IconData icon,
    VoidCallback onTap,
  ) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 64,
        height: 64,
        decoration: BoxDecoration(
          color: Colors.white,
          shape: BoxShape.circle,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.06),
              blurRadius: 8,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Center(child: Icon(icon, size: 28, color: Colors.grey[800])),
      ),
    );
  }
}
