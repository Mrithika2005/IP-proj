<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flickerazzi_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle comment submission via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text']) && isset($_POST['movie_id'])) {
    $comment_text = $conn->real_escape_string($_POST['comment_text']);
    $movie_id = (int)$_POST['movie_id'];

    // Insert the comment into the database with the movie_id
    $sql = "INSERT INTO comments (comment_text, movie_id) VALUES ('$comment_text', $movie_id)";

    if ($conn->query($sql) === TRUE) {
        echo "Comment added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    exit();
}

// Fetch all comments for a specific movie via GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['movie_id']) && isset($_GET['fetch_comments'])) {
    $movie_id = (int)$_GET['movie_id'];
    $sql = "SELECT comment_text FROM comments WHERE movie_id = $movie_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<p>" . htmlspecialchars($row['comment_text']) . "</p>";
            echo "<hr>";
            echo "</div>";
        }
    } else {
        echo "<p>No comments yet.</p>";
    }
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Flickerazzi</title>
  <!-- Include your CSS file here -->
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #111;
      color: white;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 35px;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: white;
      color: black;
      padding: 20px;
      position: relative;
    }

    .header h1 {
      margin: 0 auto;
      font-size: 40px;
      font-family: 'Baskerville';
      text-align: center;
    }

    .menu {
      font-size: 30px;
      cursor: pointer;
      position: absolute;
      top: 20px;
      right: 20px;
      z-index: 2;
    }

    /* Sidebar menu styling */
    .sidebar {
    height: 100%;
    width: 0; /* Initially hidden */
    position: fixed;
    top: 0;
    right: 0; /* Positioned to the right */
    background-color: rgba(17, 17, 17, 0.8);
    transition: 0.3s;
    padding-top: 60px;
    z-index: 999; /* Added z-index for layering */
}

    /* Links inside the sidebar */
    .sidebar a {
      padding: 10px 15px;
      text-decoration: none;
      font-size: 20px;
      color: #fff;
      display: block;
      transition: 0.3s;
    }

    /* Change color on hover */
    .sidebar a:hover {
      background-color: #575757;
    }

    /* Close button inside the sidebar */
    .closebtn {
      position: absolute;
      top: 10px;
      left: 25px;
      font-size: 36px;
      cursor: pointer;
    }

    .main-content {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      padding: 30px;
      transition: 0.5s;
    }

    .slider {
      position: relative;
      width: 54%;
      margin-right: 10px;
      overflow: hidden;
      white-space: nowrap;
    }

    .slider-container {
      display: flex;
      transition: transform 0.5s ease-in-out;
    }

    .slider img {
      width: 100%;
      height: 484px;
      border-radius: 10px;
      display: inline-block;
      cursor: pointer;
      position: relative;
    }

    .slider .info-box {
      position: absolute;
      bottom: 0;
      width: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      color: white;
      padding: 10px;
      font-size: 18px;
      text-align: center;
      visibility: hidden;
      opacity: 0;
      transition: opacity 0.3s;
    }

    .slider img:hover + .info-box {
      visibility: visible;
      opacity: 1;
    }

    .arrow-left, .arrow-right {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      font-size: 30px;
      color: white;
      background-color: rgba(0, 0, 0, 0.5);
      padding: 10px;
      border-radius: 50%;
      cursor: pointer;
    }

    .arrow-left {
      left: 10px;
    }

    .arrow-right {
      right: 10px;
    }

    .popular-movies {
      width: 40%;
    }

    .popular-movies h2 {
      font-size: 24px;
      margin-bottom: 15px;
    }

    .popular-movies .movies-list {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }

    .movie {
      position: relative;
    }

    .movie img {
      width: 181.5px;
      height: 272.25px;
      border-radius: 5px;
      cursor: pointer;
    }

    .movie .movie-title {
      position: absolute;
      bottom: 0;
      width: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      color: white;
      text-align: center;
      padding: 5px;
      font-size: 14px;
      display: none;
    }

    .movie:hover .movie-title {
      display: block;
    }

    .stars {
      color: gold;
      margin-top: 5px;
      font-size: 14px;
      text-align: center;
    }

    .tamil-movies {
      margin: 40px 0;
      padding-left: 30px;
    }

    .tamil-movies h2 {
      font-size: 24px;
      margin-bottom: 15px;
    }

    .tamil-movies .movies-list {
      display: flex;
      justify-content: flex-start;
      gap: 22px;
      padding-left: 10px;
      flex-wrap: wrap;
    }

    .tamil-movie {
      margin-bottom: 20px;
      position: relative;
    }

    .tamil-movie img {
      width: 181.5px;
      height: 272.25px;
      border-radius: 5px;
      cursor: pointer;
    }

    .tamil-movie .movie-title {
      position: absolute;
      bottom: 0;
      width: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      color: white;
      text-align: center;
      padding: 5px;
      font-size: 14px;
      display: none;
    }

    .tamil-movie:hover .movie-title {
      display: block;
    }

    .modal {
      display: none;
      position: fixed;
      right: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.8);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background-color: white;
      color: black;
      width: 80%;
      max-width: 600px;
      padding: 20px;
      border-radius: 10px;
      position: relative;
      max-height: 90vh;
      overflow-y: auto;
    }

    .modal img {
      width: 100%;
      height: auto;
      border-radius: 5px;
    }

    .modal .close {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 24px;
      cursor: pointer;
    }

    .comment-section {
      margin-top: 20px;
    }

    .comment-section h3 {
      margin-bottom: 10px;
      color: #333;
    }

    #commentInput {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      resize: vertical;
      font-size: 14px;
    }

    #commentForm button {
      margin-top: 10px;
      padding: 8px 16px;
      background-color: #333;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
    }

    #commentsList {
      margin-top: 20px;
      max-height: 200px;
      overflow-y: auto;
      background-color: #f9f9f9;
      padding: 10px;
      border-radius: 5px;
    }

    .comment {
      background-color: #fff;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 5px;
      box-shadow: 0 0 5px rgba(0,0,0,0.1);
      font-size: 14px;
      color: #333;
    }

    section {
      padding: 20px 20px; /* Adding padding for spacing */
      min-height: 30vh; /* Ensure each section has a minimum height */
      background-color: #111; /* Optional: a light background for sections */
    }
  </style>
</head>
<body>
    
  <div class="header">
    <h1>F L I C K E R A Z Z I</h1>
        <div class="menu" onclick="toggleMenu()">&#9776;</div>
    </div>

  <!-- Sidebar (Initially hidden) -->
  <div id="mySidebar" class="sidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="toggleMenu()">&times;</a>
    <a href="#popular">Recent</a>
    <a href="#tamil">Tamil</a>
    <a href="#hindi">Hindi</a>
    <a href="trailer.html">Trailer</a>
    <a href="titlee.html">Sign Out</a>

  </div>
  <script>
    function toggleMenu() {
      const sidebar = document.getElementById("mySidebar");

      // Toggle sidebar width to open/close
      if (sidebar.style.width === "250px") {
        sidebar.style.width = "0";
      } else {
        sidebar.style.width = "250px";
      }
    }
  </script>
  </div>

<div class="main-content">
    <!-- Main content code goes here -->
  </div>
  <section id="popular">
  <div class="main-content">
    <div class="slider">
      <div class="slider-container">
        <img src="love next door.jpg" alt="Movie 1" onclick="openModal('love next door.jpg', 'LOVE NEXT DOOR', 'This is a review for Love Next Door. It is an engaging film that captivates the audience with its storyline and characters. The cinematography and acting are top-notch, making it a must-watch for movie enthusiasts.', '1')"
        ondblclick="redirectToLink('https://www.youtube.com/watch?v=C3TpiZndAOo')">
        <div class="info-box">LOVE NEXT DOOR</div>
        <img src="it ends with us.jpg" alt="Movie 2" onclick="openModal('it ends with us.jpg', 'IT ENDS WITH US', 'This is a review for It Ends With Us. A beautifully crafted film that explores deep emotional themes with grace and sensitivity. The performances are heartfelt, and the narrative is powerful and thought-provoking.','2')">
        <div class="info-box">IT ENDS WITH US</div>
        <img src="goblin1.jpeg" alt="Movie 3" onclick="openModal('goblin1.jpeg', 'END GAME', 'End Game, a mesmerizing tale that blends fantasy with reality. The special effects and storytelling are remarkable, making it a memorable experience for viewers.','3')">
        <div class="info-box">END GAME</div>
      </div>
      <div class="arrow-left" onclick="prevSlide()">&#10094;</div>
      <div class="arrow-right" onclick="nextSlide()">&#10095;</div>
    </div>
    
    <div class="popular-movies">
      <h2>Popular Movies</h2>
      <div class="movies-list">
        <div class="movie" onclick="openModal('dark1.jpg', 'DARK', 'Dark is a gripping thriller that keeps you on the edge of your seat. With a complex plot and intense performances, it’s a film that demands your full attention.','4')">
          <img src="dark.jpg" alt="Movie Poster">
          <div class="movie-title">DARK</div>
          <div class="stars">★★★★☆</div>
        </div>
        <div class="movie" onclick="openModal('goat1.jpeg', 'GOAT', 'GOAT is an inspiring story of perseverance and success. The film is a roller-coaster of emotions, with a stellar cast and a compelling narrative that leaves a lasting impression.','5')">
          <img src="GOAT.jpeg" alt="Movie Poster">
          <div class="movie-title">GOAT</div>
          <div class="stars">★★★★★</div>
        </div>
      </div>
    </div>
  </div>
  </section>

<section id="hindi">
  <div class="tamil-movies">
    <h2>Hindi Movies</h2>
    <div class="movies-list">
      <div class="tamil-movie" onclick="openModal('831.jpeg', '83', '83 is an engaging drama with a captivating storyline and powerful performances. The film offers a fresh perspective and is a treat for fans of Hindi cinema.','6')">
        <img src="83.jpeg" alt="hindi Movie 1 Poster">
        <div class="movie-title">83</div>
        <div class="stars">★★★★☆</div>
      </div>
      <div class="tamil-movie" onclick="openModal('padmaavat1.jpeg', 'PADMAAVAT', 'Padmaavat combines thrilling action with a compelling story. The film’s high energy and intense scenes make it an exciting watch for action lovers.','7')">
        <img src="padmaavat.jpeg" alt="Hindi Movie 2 Poster">
        <div class="movie-title">PADMAAVAT</div>
        <div class="stars">★★★★☆</div>
      </div>
      <div class="tamil-movie" onclick="openModal('kabir-singh1.jpg', 'KABIR SINGH', 'Kabir Singh is a captivating film with a rich narrative and impressive visuals. The strong performances and engaging plot make it a standout in Hindi cinema.','8')">
        <img src="kabir singh.jpeg" alt="Hindi Movie 3 Poster">
        <div class="movie-title">KABIR SINGH</div>
        <div class="stars">★★★★☆</div>
      </div>
      <div class="tamil-movie" onclick="openModal('pathaan1.jpg', 'PATHAAN', 'Pathaan is an emotional journey that explores deep themes with sensitivity and grace. The powerful performances and storytelling make it a memorable film.','9')">
        <img src="pathaan.jpg" alt="Hindi Movie 4 Poster">
        <div class="movie-title">PATHAAN</div>
        <div class="stars">★★★★☆</div>
      </div>
      <div class="tamil-movie" onclick="openModal('dangal 1.jpg', 'DANGAL', 'Dangal offers an intriguing narrative with unexpected twists and engaging characters. It’s a gripping film that will keep viewers hooked until the very end.','10')">
        <img src="dangal.jpg" alt="Hindi Movie 5 Poster">
        <div class="movie-title">DANGAL</div>
        <div class="stars">★★★★☆</div>
      </div>
      <div class="tamil-movie" onclick="openModal('satyaprem1.jpg', 'SATYAPREM KE KATHA', 'Satyaprem Ke Katha is a visually stunning film that combines great direction with a compelling story. The film’s excellent production values and performances are noteworthy.','11')">
        <img src="satyaprem.jpeg" alt="Hindi Movie 6 Poster">
        <div class="movie-title">SATYAPREM KE KATHA</div>
        <div class="stars">★★★★☆</div>
      </div>
      <div class="tamil-movie" onclick="openModal('chennai%20express1.webp', 'CHENNAI EXPRESS', 'Chennai Express is a captivating drama that explores human emotions and relationships with depth. Its strong narrative and performances make it a standout film.','12')">
        <img src="chennai express.jpg" alt="Hindi Movie 7 Poster">
        <div class="movie-title">CHENNAI EXPRESS</div>
        <div class="stars">★★★★☆</div>
      </div>
    </div>
  </div>
  </section>

  <section id="tamil">
  <div class="tamil-movies">
    <h2>Tamil Movies</h2>
    <div class="movies-list">
      <div class="tamil-movie" onclick="openModal('maanadu1.jpg', 'MAANADU', 'Maanadu is an engaging drama with a captivating storyline and powerful performances. The film offers a fresh perspective and is a treat for fans of Tamil cinema.','13')">
        <img src="maanadu.webp" alt="Tamil Movie 1 Poster">
        <div class="movie-title">MAANADU</div>
        <div class="stars">★★★★★</div>
      </div>
      <div class="tamil-movie" onclick="openModal('vikram1.webp', 'VIKRAM', 'Vikram combines thrilling action with a compelling story. The film’s high energy and intense scenes make it an exciting watch for action lovers.','14')">
        <img src="vikram.jpg" alt="Tamil Movie 2 Poster">
        <div class="movie-title">VIKRAM</div>
        <div class="stars">★★★★★</div>
      </div>
      <div class="tamil-movie" onclick="openModal('aruvi1.webp', 'ARUVI', 'Aruvi is a captivating film with a rich narrative and impressive visuals. The strong performances and engaging plot make it a standout in Tamil cinema.','15')">
        <img src="aruvi.webp" alt="Tamil Movie 3 Poster">
        <div class="movie-title">ARUVI</div>
        <div class="stars">★★★★☆</div>
      </div>
      <div class="tamil-movie" onclick="openModal('athomugam1.jpg', 'ATHOMUGAM', 'Athomugam is an emotional journey that explores deep themes with sensitivity and grace. The powerful performances and storytelling make it a memorable film.','16')">
        <img src="athomugam.jpg" alt="Tamil Movie 4 Poster">
        <div class="movie-title">ATHOMUGAM</div>
        <div class="stars">★★★★☆</div>
      </div>
      <div class="tamil-movie" onclick="openModal('okk1.jpg', 'O KADHAL KANMANI', 'OK KANMANI offers an intriguing narrative with unexpected twists and engaging characters. It’s a gripping film that will keep viewers hooked until the very end.','17')">
        <img src="okk.jpeg" alt="Tamil Movie 5 Poster">
        <div class="movie-title">OK KANMANI</div>
        <div class="stars">★★★★★</div>
      </div>
      <div class="tamil-movie" movie_id="7" onclick="openModal('thambi1.jpeg', 'THAMBI', 'Thambi is a visually stunning film that combines great direction with a compelling story. The film’s excellent production values and performances are noteworthy.','18')">
        <img src="thambi.jpg" alt="Tamil Movie 6 Poster">
        <div class="movie-title">THAMBI</div>
        <div class="stars">★★★★☆</div>
      </div>
      <div class="tamil-movie" onclick="openModal('961.webp', '96', '96 is a captivating drama that explores human emotions and relationships with depth. Its strong narrative and performances make it a standout film.','19')">
        <img src="96.jpg" alt="Tamil Movie 7 Poster">
        <div class="movie-title">96</div>
        <div class="stars">★★★★☆</div>
      </div>
    </div>
  </div>
  </section>

  <div class="modal" id="movieModal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <img id="movieImage" src="" alt="Movie Poster">
      <h2 id="movieTitle">Movie Title</h2>
      <p id="movieDescription">Movie Description</p>
          
        <!-- Comment Section -->
        <div class="comment-section">
            <h3>Comments</h3>
            <form id="commentForm">
                <textarea id="commentInput" name="comment_text" rows="3" placeholder="Add a comment..." required></textarea>
                <button type="submit">Submit</button>
                <input type="hidden" id="movieId" name="movie_id">
            </form>
            <div id="commentsList"></div>
        </div>
    </div>
</div>

<!-- jQuery Library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let slideIndex = 0;

function showSlides() {
  const slides = document.querySelectorAll('.slider img');
  const totalSlides = slides.length;
  const sliderContainer = document.querySelector('.slider-container');
  sliderContainer.style.transform = `translateX(-${slideIndex * 100}%)`;
}

function nextSlide() {
  const slides = document.querySelectorAll('.slider img');
  const totalSlides = slides.length;
  slideIndex = (slideIndex + 1) % totalSlides;
  showSlides();
}

function prevSlide() {
  const slides = document.querySelectorAll('.slider img');
  const totalSlides = slides.length;
  slideIndex = (slideIndex - 1 + totalSlides) % totalSlides;
  showSlides();
}

    let currentMovieId = 0;

    function openModal(imageSrc, title, description, movieId) {
        currentMovieId = movieId;
        console.log("Open Modal with: ", title);
        document.getElementById('movieImage').src = imageSrc;
        document.getElementById('movieTitle').textContent = title;
        document.getElementById('movieDescription').textContent = description;
        document.getElementById('movieId').value = movieId;
        document.getElementById('movieModal').style.display = 'flex';
        
        // Load comments for this movie
        loadComments(movieId);
    }

    function redirectToLink(url) {
        window.location.href = url; // Redirects to the specified URL
    }

    function closeModal() {
        document.getElementById('movieModal').style.display = 'none';
    }

    function loadComments(movieId) {
        $.ajax({
            url: "index.php",
            type: "GET",
            data: { movie_id: movieId, fetch_comments: true },
            success: function(data) {
                $('#commentsList').html(data);
            }
        });
    }

    $(document).ready(function() {
        // Handle form submission via AJAX
        $("#commentForm").on("submit", function(e) {
            e.preventDefault();
            
            const commentText = $("#commentInput").val();
            const movieId = $("#movieId").val();

            $.ajax({
                type: "POST",
                url: "index.php",
                data: { comment_text: commentText, movie_id: movieId },
                success: function(response) {
                    $("#commentInput").val("");  // Clear the input
                    loadComments(movieId);  // Reload comments after submission
                    alert(response);  // Show success message
                },
                error: function() {
                    alert("Error submitting comment.");
                }
            });
        });
    });
</script>

</body>
</html>
