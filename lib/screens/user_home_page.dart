import 'package:flutter/material.dart';

class UserHomePage extends StatelessWidget {
  const UserHomePage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Page d\'accueil'),
      ),
      body: Center(
        child: const Text(
          'Bienvenue sur la page d\'accueil de l\'utilisateur !',
          style: TextStyle(fontSize: 20),
        ),
      ),
    );
  }
}
