import 'dart:convert'; // Nécessaire pour json.encode et json.decode
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:http/http.dart' as http;
import 'signup_screen.dart'; // Import de votre fichier pour la page de création de compte
import 'user_home_page.dart'; // Import de la page d'accueil de l'utilisateur

// Fonction de connexion avec vérification du rôle
Future<Map<String, dynamic>> loginUser(String email, String password) async {
  final url = 'http://127.0.0.1:8000/api/login'; // Remplacez par l'URL de votre API

  try {
    final response = await http.post(
      Uri.parse(url),
      headers: {'Content-Type': 'application/json'},
      body: json.encode({'email': email, 'password': password}),
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      return {'message': 'Erreur: ${response.statusCode}. ${response.body}'};
    }
  } catch (error) {
    return {
      'message': 'Une erreur est survenue. Veuillez réessayer plus tard.'
    };
  }
}

class LoginScreen extends StatefulWidget {
  const LoginScreen({Key? key}) : super(key: key);

  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final emailController = TextEditingController();
  final passwordController = TextEditingController();
  String errorMessage = "";

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        width: double.infinity,
        height: double.infinity,
        color: Colors.grey[200],
        child: Center(
          child: Card(
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(15),
            ),
            elevation: 5,
            color: Colors.white,
            child: Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  // Logo
                  Image.asset(
                    'images/img.png', // Assurez-vous que votre image est dans ce chemin
                    height: 80,
                  ),
                  const SizedBox(height: 20),
                  // Titre
                  Text(
                    "Login",
                    style: GoogleFonts.nunito(
                      fontSize: 22,
                      fontWeight: FontWeight.bold,
                      color: Color(0xFF012970),
                    ),
                  ),
                  const SizedBox(height: 10),
                  Text(
                    "Enter your credentials to log in.",
                    style: GoogleFonts.openSans(
                      fontSize: 14,
                      color: const Color.fromARGB(255, 50, 50, 50),
                    ),
                  ),
                  const SizedBox(height: 20),
                  // Champs Email
                  TextFormField(
                    controller: emailController,
                    decoration: InputDecoration(
                      labelText: "Email",
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                      focusedBorder: OutlineInputBorder(
                        borderSide: BorderSide(
                          color: Colors.blue,
                          width: 2,
                        ),
                      ),
                      floatingLabelBehavior: FloatingLabelBehavior.auto,
                    ),
                    keyboardType: TextInputType.emailAddress,
                  ),
                  const SizedBox(height: 15),
                  // Champs Password
                  TextFormField(
                    controller: passwordController,
                    obscureText: true,
                    decoration: InputDecoration(
                      labelText: "Password",
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                      focusedBorder: OutlineInputBorder(
                        borderSide: BorderSide(
                          color: Colors.blue,
                          width: 2,
                        ),
                      ),
                      floatingLabelBehavior: FloatingLabelBehavior.auto,
                    ),
                  ),
                  const SizedBox(height: 20),
                  // Bouton de connexion
                  ElevatedButton(
                    onPressed: () async {
                      String email = emailController.text;
                      String password = passwordController.text;

                      if (email.isNotEmpty && password.isNotEmpty) {
                        var result = await loginUser(email, password);

                        if (result.containsKey('message')) {
                          setState(() {
                            errorMessage = result['message'];
                          });
                        } else {
                          // Vérifiez si le rôle est égal à 2
                          if (result['role_id'] == 2) {
                            // Rediriger vers la page d'accueil avec pushReplacement
                            Navigator.pushReplacement(
                              context,
                              MaterialPageRoute(
                                builder: (context) => const UserHomePage(),
                              ),
                            );
                          } else {
                            setState(() {
                              errorMessage =
                                  'Vous devez être un employé pour vous connecter.';
                            });
                          }
                        }
                      } else {
                        setState(() {
                          errorMessage = "Veuillez remplir tous les champs";
                        });
                      }
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.blue,
                      minimumSize: const Size(double.infinity, 50),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                    ),
                    child: const Text(
                      "Login",
                      style: TextStyle(color: Colors.white, fontSize: 16),
                    ),
                  ),
                  const SizedBox(height: 10),
                  // Lien vers la page de création de compte
                  GestureDetector(
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => SignupScreen(),
                        ),
                      );
                    },
                    child: Text(
                      "Don't have an account? Create one",
                      style: GoogleFonts.openSans(
                        color: Colors.blue,
                        fontSize: 14,
                      ),
                    ),
                  ),
                  if (errorMessage.isNotEmpty)
                    Padding(
                      padding: const EdgeInsets.all(8.0),
                      child: Text(
                        errorMessage,
                        style: TextStyle(color: Colors.red),
                      ),
                    ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
