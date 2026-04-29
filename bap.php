<?php
session_start();
include 'db.php';

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ================= SEND OTP FUNCTION =================
function sendOTP($email, $otp){

  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    $mail->Username = 'priemumvlogs@gmail.com';
    $mail->Password = 'ztcchuxegpvdmole';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('priemumvlogs@gmail.com', 'Pinecrest Kashmir Travels');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Partner Registration OTP";

    $mail->Body = "
      <div style='font-family:Arial; line-height:1.6; max-width:500px; margin:auto;'>
        <h2 style='color:#007bff;'>Pinecrest Kashmir Travels</h2>
        <p>Dear Partner,</p>
        <p>Thank you for registering as a partner.</p>
        <p>Your OTP for verification is:</p>
        <h1 style='color:#007bff;'>$otp</h1>
        <p>Please enter this OTP to complete your registration.</p>
        <p style='font-size:13px;color:#555;'>Do not share this OTP.</p>
      </div>
    ";

    $mail->send();
    return true;

  } catch (Exception $e) {
    echo "Error: " . $mail->ErrorInfo;
    return false;
  }
}

// ================= REGISTER =================
if(isset($_POST['register'])){

  $fname = mysqli_real_escape_string($conn, $_POST['fname']);
  $lname = mysqli_real_escape_string($conn, $_POST['lname']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $pass  = $_POST['password'];
  $addr  = mysqli_real_escape_string($conn, $_POST['address']);

  // CHECK EMAIL
  $check = $conn->query("SELECT id FROM users WHERE email='$email'");
  if($check->num_rows > 0){
    echo "<script>alert('Email already registered');</script>";
    exit();
  }

  // OTP GENERATE
  $otp = rand(100000,999999);

  // STORE TEMP DATA
  $_SESSION['otp'] = $otp;
  $_SESSION['temp_user'] = [
    'fname'=>$fname,
    'lname'=>$lname,
    'email'=>$email,
    'password'=>$pass,
    'address'=>$addr,
    'role'=>'partner' // 🔥 IMPORTANT
  ];

  // SEND OTP
  if(sendOTP($email,$otp)){
    header("Location: po.php");
    exit();
  } else {
    echo "<script>alert('OTP send failed');</script>";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Become Partner</title>

<style>

body{
  margin:0;
  font-family:Arial;
}

/* TOPBAR */
.topbar{
  text-align:right;
  padding:10px 30px;
  font-size:14px;
}

/* LINE */
.line{
  height:3px;
  background:orange;
}

/* NAV */
.nav{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:15px 30px;
}

.logo{
  display:flex;
  align-items:center;
  gap:10px;
  font-weight:bold;
}

.menu{
  display:flex;
  gap:20px;
  align-items:center;
}

.blue-btn{
  background:#007bff;
  color:white;
  padding:8px 14px;
  border-radius:6px;
  border:none;
  cursor:pointer;
}

.black-btn{
  background:black;
  color:white;
  padding:8px 14px;
  border-radius:6px;
  border:none;
  cursor:pointer;
}

/* HERO */
.hero{
  position:relative;
}

.hero img{
  width:100%;
  height:520px;
  object-fit:cover;
}

/* FORM BOX */
.form-box{
  position:absolute;
  top:70px;
  left:80px;
  width:340px;
  background:white;
  padding:25px;
  border-radius:12px;
  box-shadow:0 10px 25px rgba(0,0,0,0.3);
}

/* INPUT */
.form-box input{
  width:100%;
  padding:10px;
  margin:8px 0;
  border:1px solid #ccc;
  border-radius:6px;
  box-sizing:border-box;
}

/* ROW */
.row{
  display:flex;
  gap:8px;
}

/* CHECKBOX */
.checkbox-line{
  display:flex;
  align-items:center;
  gap:6px;
  margin:8px 0;
  font-size:14px;
}

.checkbox-line input{
  width:auto;
  margin:0;
}

/* LOGIN TEXT */
.login-text{
  text-align:left;
  margin:8px 0 12px 0;
  font-size:14px;
}

.login-text a{
  color:#007bff;
  text-decoration:none;
}

.login-text a:hover{
  text-decoration:underline;
}

/* BUTTON */
button{
  background:#007bff;
  color:white;
  padding:12px;
  border:none;
  width:100%;
  border-radius:6px;
  cursor:pointer;
}

button:hover{
  background:#0056b3;
}

/* SIDE TEXT */
.side-text{
  position:absolute;
  right:80px;
  top:150px;
  width:320px;
  color:white;
}

/* FOOTER */
.footer{
  background:#777;
  color:white;
  text-align:center;
  padding:40px;
}

.footer-container{
  display:flex;
  justify-content:space-between;
  gap:40px;
  text-align:left;
}

.footer-left a{
  display:block;
  color:white;
  text-decoration:none;
  margin:6px 0;
}

.footer{
  background:#777;
  color:white;
  text-align:center;
  padding:40px;
}

.footer-container{
  display:flex;
  justify-content:space-between;
  gap:40px;
  text-align:left;
}

.footer-left a{
  display:block;
  color:white;
  text-decoration:none;
  margin:6px 0;
}

.footer-left a:hover{
  color:#ddd;
}

.footer-center p,
.footer-right p{
  margin:6px 0;
}

body{
  margin:0;
  font-family:Arial;
}

/* TOPBAR */
.topbar{
  text-align:right;
  padding:10px 30px;
  font-size:14px;
}

/* LINE */
.line{
  height:3px;
  background:orange;
}

/* NAV */
.nav{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:15px 30px;
  flex-wrap:wrap; /* ✅ important */
}

.logo{
  display:flex;
  align-items:center;
  gap:10px;
  font-weight:bold;
}

.menu{
  display:flex;
  gap:20px;
  align-items:center;
  flex-wrap:wrap; /* ✅ */
}

.blue-btn,
.black-btn{
  padding:8px 14px;
  border-radius:6px;
  border:none;
  cursor:pointer;
}

.blue-btn{
  background:#007bff;
  color:white;
}

.black-btn{
  background:black;
  color:white;
}

/* HERO */
.hero{
  position:relative;
}

.hero img{
  width:100%;
  height:520px;
  object-fit:cover;
}

/* FORM BOX */
.form-box{
  position:absolute;
  top:70px;
  left:80px;
  width:340px;
  background:white;
  padding:25px;
  border-radius:12px;
  box-shadow:0 10px 25px rgba(0,0,0,0.3);
}

/* INPUT */
.form-box input{
  width:100%;
  padding:10px;
  margin:8px 0;
  border:1px solid #ccc;
  border-radius:6px;
  box-sizing:border-box;
}

/* ROW */
.row{
  display:flex;
  gap:8px;
}

/* CHECKBOX */
.checkbox-line{
  display:flex;
  align-items:center;
  gap:6px;
  margin:8px 0;
  font-size:14px;
}

.checkbox-line input{
  width:auto;
  margin:0;
}

/* LOGIN TEXT */
.login-text{
  text-align:left;
  margin:8px 0 12px 0;
  font-size:14px;
}

.login-text a{
  color:#007bff;
  text-decoration:none;
}

.login-text a:hover{
  text-decoration:underline;
}

/* BUTTON */
button{
  background:#007bff;
  color:white;
  padding:12px;
  border:none;
  width:100%;
  border-radius:6px;
  cursor:pointer;
}

button:hover{
  background:#0056b3;
}

/* SIDE TEXT */
.side-text{
  position:absolute;
  right:80px;
  top:150px;
  width:320px;
  color:white;
}

/* FOOTER */
.footer{
  background:#777;
  color:white;
  text-align:center;
  padding:40px;
}

.footer-container{
  display:flex;
  justify-content:space-between;
  gap:40px;
  text-align:left;
  flex-wrap:wrap; /* ✅ */
}

.footer-left a{
  display:block;
  color:white;
  text-decoration:none;
  margin:6px 0;
}

.footer-left a:hover{
  color:#ddd;
}

.footer-center p,
.footer-right p{
  margin:6px 0;
}

/* ============================= */
/* 📱 RESPONSIVE MEDIA QUERIES */
/* ============================= */

@media(max-width:1024px){

  .form-box{
    left:40px;
    width:300px;
  }

  .side-text{
    right:40px;
    width:260px;
  }
}

/* ================= MOBILE ================= */

@media(max-width:768px){

  /* NAV FIX (ONE LINE SCROLL) */
  .nav{
    flex-direction:column;
    align-items:flex-start;
    gap:10px;
  }

  .menu{
  display:flex;
  gap:15px;
  align-items:center;
  flex-wrap:wrap;   /* allow next line */
  width:100%;
}

/* SIGN IN niche + left side */
.black-btn{
  display:block;
  margin-top:10px;
  margin-left:0;    /* ✅ left align */
}
  .menu span,
  .menu .blue-btn,
  .menu .black-btn{
    flex-shrink:0;           /* ✅ squeeze nahi honge */
  }

  .menu::-webkit-scrollbar{
    display:none;            /* optional */
  }

  /* HERO */
  .hero img{
    height:260px;
  }

  /* FORM */
  .form-box{
    position:static;
    margin:10px auto;
    width:90%;
  }

  /* SIDE TEXT */
  .side-text{
    position:static;
    margin:20px;
    width:auto;
    color:black;
  }

  /* INPUT ROW STACK */
  .row{
    flex-direction:column;
  }

  /* FOOTER STACK */
  .footer-container{
    flex-direction:column;
    gap:20px;
  }
}

/* ================= SMALL MOBILE ================= */

@media(max-width:480px){

  .topbar{
    text-align:center;
    padding:10px;
  }

  .hero img{
    height:300px;
  }

  .form-box{
    padding:15px;
  }

  .side-text{
    margin:10px;
  }
}

</style>

</head>

<body>

<div class="topbar">
  Support 12/7 : XXXXXXXX
</div>

<div class="line"></div>

<div class="nav">

<div class="logo">
<img src="Logo.png" height="45">
<span>Pinecrest Kashmir Travels</span>
</div>

<div class="menu">
<span onclick="location='index.php'">Home</span>
<span onclick="location='hotel.php'">Hotel</span>
<span onclick="location='car.php'">Car</span>
<span onclick="location='cu.php'">Contact</span>

<span class="blue-btn" onclick="location='bap.php'">Become a Partner</span>
<span class="black-btn" onclick="location='signin.php'">Sign In</span>
</div>

</div>

<div class="hero">
<img src="kashmir.png">

<div class="form-box">
<h2>Become a Partner</h2>

<form method="POST">

<div class="row">
<input name="fname" placeholder="First Name *" required>
<input name="lname" placeholder="Last Name">
</div>

<input name="email" type="email" placeholder="Email *" required>
<input name="password" type="password" placeholder="Password *" required>
<input name="address" placeholder="Address *" required>

<div class="checkbox-line">
  <input type="checkbox" required>
  <label>I accept <a href="tc.html">Terms & Conditions</a></label>
</div>

<p class="login-text">
  Already a partner? 
  <a href="pl.php">Login</a>
</p>

<button name="register">Register</button>

</form>
</div>

<div class="side-text">
<h2>Why To Become Our Partner</h2>
<p>
Grow your business with us and reach more customers easily.
</p>
</div>

</div>

<div class="footer">

<div class="footer-container">

<!-- LEFT -->
<div class="footer-left">
<h3>Quick Links</h3>

<a href="index.php">Home</a>
<a href="#tour.php">Domestic</a>
<a href="#hotel.php">Hotels</a>
<a href="#tour.php">Tour</a>
<a href="#car.php">Cars</a>

</div>

<!-- CENTER -->
<div class="footer-center">
<h3>Policies</h3>

<p onclick="location='tc.html'" style="cursor:pointer;">Terms and Conditions</p>
<p onclick="location='pp.html'" style="cursor:pointer;">Privacy Policy</p>
<p onclick="location='prp.html'" style="cursor:pointer;">Payment and Refund Policy</p>

</div>

<!-- RIGHT -->
<div class="footer-right">
<h3>Contact</h3>

<p>Phone: XXXXXXXXX</p>
<p>Email 1: yourmail1@example.com</p>
<p>Email 2: yourmail2@example.com</p>

<br>

<p onclick="window.open('https://www.instagram.com/paradisekashmirtravel1?igsh=MWpsdTJqbXdyMmJ5Mw==','_blank')" style="cursor:pointer;">
Instagram
</p>

<p onclick="window.open('https://youtube.com/@paradisekashmirtravels?si=H73kaD2uovjKG1aN')" style="cursor:pointer;">
YouTube
</p>

</div>

</div>

<p style="margin-top:20px;">© 2026 Pinecrest Kashmir Travels</p>

</div>
</body>
</html>