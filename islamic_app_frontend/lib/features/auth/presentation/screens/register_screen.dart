import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../providers/register_provider.dart';

class RegisterScreen extends StatelessWidget {
  const RegisterScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return ChangeNotifierProvider(
      create: (_) => RegisterProvider(),
      child: Scaffold(
        backgroundColor: Colors.grey[50],
        body: SafeArea(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(20),
            child: Consumer<RegisterProvider>(
              builder: (context, provider, _) {
                return Column(
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    const SizedBox(height: 20),
                    Text(
                      "إنشاء حساب جديد ✏️",
                      style: theme.textTheme.headlineMedium?.copyWith(
                        color: Colors.amber[800],
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                    const SizedBox(height: 40),

                    _buildInputField(
                      label: "الاسم الكامل",
                      hint: "ادخل اسمك الكامل",
                      icon: Icons.person_outline,
                    ),

                    const SizedBox(height: 20),

                    _buildInputField(
                      label: "البريد الإلكتروني",
                      hint: "ادخل بريدك الإلكتروني",
                      icon: Icons.email_outlined,
                    ),

                    const SizedBox(height: 20),

                    // كلمة المرور
                    _buildPasswordField(
                      context: context,
                      label: "كلمة المرور",
                      hint: "ادخل كلمة المرور",
                      isVisible: provider.isPasswordVisible,
                      onToggle: provider.togglePasswordVisibility,
                    ),

                    const SizedBox(height: 5),
                    _buildPasswordStrengthBar(context),
                    const SizedBox(height: 5),
                    Align(
                      alignment: Alignment.centerRight,
                      child: Text(
                        "ضعيف",
                        style: theme.textTheme.bodySmall?.copyWith(
                          color: Colors.red,
                        ),
                      ),
                    ),
                    const SizedBox(height: 20),

                    // تأكيد كلمة المرور
                    _buildPasswordField(
                      context: context,
                      label: "تأكيد كلمة المرور",
                      hint: "أعد إدخال كلمة المرور",
                      isVisible: provider.isConfirmPasswordVisible,
                      onToggle: provider.toggleConfirmPasswordVisibility,
                    ),

                    const SizedBox(height: 20),

                    // رقم الهاتف
                    _buildInputField(
                      label: "رقم الهاتف (اختياري)",
                      hint: "ادخل رقم هاتفك",
                      icon: Icons.phone_outlined,
                    ),

                    const SizedBox(height: 20),

                    // الموافقة على الشروط
                    Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Checkbox(
                          value: provider.agreeToTerms,
                          onChanged: (_) => provider.toggleAgreement(),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(4),
                          ),
                        ),
                        Expanded(
                          child: Text.rich(
                            TextSpan(
                              text: "أوافق على ",
                              style: theme.textTheme.bodyMedium,
                              children: [
                                TextSpan(
                                  text: "الشروط والأحكام",
                                  style: TextStyle(
                                    color: Colors.amber[800],
                                    fontWeight: FontWeight.w600,
                                  ),
                                ),
                                const TextSpan(text: " و "),
                                TextSpan(
                                  text: "سياسة الخصوصية.",
                                  style: TextStyle(
                                    color: Colors.amber[800],
                                    fontWeight: FontWeight.w600,
                                  ),
                                ),
                              ],
                            ),
                            textAlign: TextAlign.right,
                          ),
                        ),
                      ],
                    ),

                    Align(
                      alignment: Alignment.centerRight,
                      child: TextButton.icon(
                        onPressed: () {},
                        icon: const Icon(
                          Icons.description_outlined,
                          color: Colors.amber,
                        ),
                        label: Text(
                          "اقرأ الشروط والأحكام",
                          style: TextStyle(color: Colors.amber[800]),
                        ),
                      ),
                    ),

                    const SizedBox(height: 10),

                    // زر إنشاء الحساب
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton.icon(
                        icon: const Icon(Icons.arrow_forward_ios_rounded),
                        label: const Text("إنشاء الحساب"),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.green[700],
                          foregroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(vertical: 14),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12),
                          ),
                        ),
                        onPressed: provider.agreeToTerms ? () {} : null,
                      ),
                    ),

                    const SizedBox(height: 20),

                    // تسجيل الدخول
                    Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Text("لديك حساب بالفعل؟ "),
                        GestureDetector(
                          onTap: () {},
                          child: Text(
                            "تسجيل الدخول",
                            style: TextStyle(
                              color: Colors.green[700],
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 20),
                  ],
                );
              },
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildInputField({
    required String label,
    required String hint,
    required IconData icon,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.end,
      children: [
        Text(label, textAlign: TextAlign.right),
        const SizedBox(height: 6),
        TextField(
          textAlign: TextAlign.right,
          decoration: InputDecoration(
            hintText: hint,
            prefixIcon: Icon(icon),
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
            contentPadding: const EdgeInsets.symmetric(
              horizontal: 16,
              vertical: 14,
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildPasswordField({
    required BuildContext context,
    required String label,
    required String hint,
    required bool isVisible,
    required VoidCallback onToggle,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.end,
      children: [
        Text(label, textAlign: TextAlign.right),
        const SizedBox(height: 6),
        TextField(
          obscureText: !isVisible,
          textAlign: TextAlign.right,
          decoration: InputDecoration(
            hintText: hint,
            prefixIcon: IconButton(
              icon: Icon(
                isVisible ? Icons.visibility : Icons.visibility_off_outlined,
              ),
              onPressed: onToggle,
            ),
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
            contentPadding: const EdgeInsets.symmetric(
              horizontal: 16,
              vertical: 14,
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildPasswordStrengthBar(BuildContext context) {
    return LinearProgressIndicator(
      value: 0.2,
      color: Colors.red,
      backgroundColor: Colors.grey[300],
      minHeight: 4,
      borderRadius: BorderRadius.circular(20),
    );
  }
}
