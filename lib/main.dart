import 'package:flutter/material.dart';
import 'screens/login_screen.dart';
import 'package:sqflite_common_ffi/sqflite_ffi.dart';


void main() {
  runApp(const MyApp());
  sqfliteFfiInit();
  databaseFactory = databaseFactoryFfi;
}

class MyApp extends StatelessWidget {
  const MyApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      home: LoginScreen(),
    );
  }
}
