# Voice-Controlled Motor System

## Overview
This project demonstrates a voice-controlled motor system that:
- Uses Web Speech API for voice recognition in the browser.
- Saves commands in a MySQL database via PHP.
- Simulates the motor control task on Tinkercad with Arduino UNO + Servo.
- Provides ESP32 code for real hardware implementation with ESC/Servo.

---

## Features
- Voice recognition via Web Speech API.
- Commands stored in MySQL database (Forward, Backward).
- Endpoint for ESP32 to fetch the latest command.
- Tinkercad simulation for testing.
- ESP32 final code for real deployment.

---

## Project Structure
/motor
│
├── index.html         # Voice recognition page
├── saveCommand.php    # Saves commands to DB
├── getCommand.php     # Fetches latest command
└── README.md          # Documentation

---

## Database Setup
1. Start XAMPP → Apache + MySQL.
2. Open: http://localhost/phpmyadmin
3. Create database:
motor
4. Run this SQL:
CREATE TABLE IF NOT EXISTS commands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    command VARCHAR(50) NOT NULL
);

---

## File Setup
Place all files in:
C:\xampp\htdocs\motor\
Access the web app:
http://localhost/motor/index.html

---

## index.html (Voice Recognition UI)
Captures voice commands and sends them to the server using fetch API.

---

## Tinkercad Simulation
To represent the task without real hardware:

Components:
- Arduino UNO
- Servo Motor (Signal → Pin 9, VCC → 5V, GND → GND)

Simulation code:
#include <Servo.h>

Servo myservo;
const int servoPin = 9;

void setup() {
  Serial.begin(9600);
  myservo.attach(servoPin);
  myservo.write(90);
  Serial.println("Type Forward or Backward:");
}

void loop() {
  if (Serial.available()) {
    String command = Serial.readStringUntil('\n');
    command.trim();
    if (command == "Forward") {
      myservo.write(180);
      delay(1000);
      myservo.write(90);
    } else if (command == "Backward") {
      myservo.write(0);
      delay(1000);
      myservo.write(90);
    } else {
      Serial.println("Unknown command");
    }
  }
}

---

## ESP32 Code for Real Hardware
#include <WiFi.h>
#include <HTTPClient.h>
#include <ESP32Servo.h>

const char* ssid       = "Your_SSID";
const char* password   = "Your_PASSWORD";
const char* serverName = "http://your-ip/motor/getCommand.php";

int motorPin = 5;
Servo esc;

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
  }

  esc.setPeriodHertz(50);
  esc.attach(motorPin, 1000, 2000);
  esc.writeMicroseconds(1000);
  delay(1000);
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverName);
    int httpCode = http.GET();
    if (httpCode == 200) {
      String cmd = http.getString();
      cmd.trim();
      if (cmd == "Forward") rotateMotor(true);
      if (cmd == "Backward") rotateMotor(false);
    }
    http.end();
  }
  delay(2000);
}

void rotateMotor(bool forward) {
  if (forward) {
    esc.writeMicroseconds(2000);
    delay(1000);
    esc.writeMicroseconds(1000);
  } else {
    esc.writeMicroseconds(1000);
  }
}

---

## How It Works
- User speaks Forward or Backward → stored in DB via PHP.
- ESP32 fetches latest command and controls ESC/Servo.
- Tinkercad simulation mimics this process with Arduino UNO.

---

## Requirements
- XAMPP (Apache + MySQL)
- Chrome browser (Web Speech API)
- Arduino IDE (for ESP32 code)
- Tinkercad account (for simulation)

---

## License
MIT
