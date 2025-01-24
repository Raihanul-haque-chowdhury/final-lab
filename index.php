    <?php
    $conn = new mysqli("localhost", "root", "", "library_management");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
    $sql = "SELECT id, title, author, yearofpublication, genre FROM books";
    $result = $conn->query($sql);
    $books = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }
    $conn->close();

    
    $tokens = [];
    if (file_exists('token.json')) {
        $json = file_get_contents('token.json');
        $tokens = json_decode($json, true) ?? [];
    }

    
    $usedTokens = [];
    if (file_exists('used_tokens.json')) {
        $json = file_get_contents('used_tokens.json');
        $usedTokens = json_decode($json, true) ?? [];
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="style.css">
        <title>Library Management</title>
    </head>
    <body>
        <main>
        <aside class="box3">
        <h2>Token Used</h2>
        <ul class="token-list">
            <?php foreach ($usedTokens as $used): ?>
                <li><strong><?php echo htmlspecialchars($used['token']); ?></strong></li>
            <?php endforeach; ?>
        </ul>
        </aside>
            <div>
                <section>
                <div class="box1">
                <h2 class="header-title">All Database Books</h2>
                        <ul>
                            <?php foreach ($books as $book): ?>
                                <li><?php echo htmlspecialchars($book['title']) . " by " . htmlspecialchars($book['author']) . " at " . htmlspecialchars($book['yearofpublication']); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="box1">
                    <h2 class="header-title">Add or Remove Book</h2>
                        <form action="add_remove_book.php" method="post">
                            <input type="text" name="title" placeholder="Book Title" required>
                            <input type="text" name="author" placeholder="Author" required>
                            <input type="number" name="yearofpublication" placeholder="Year of Publication" required>
                            <input type="text" name="genre" placeholder="Genre" required>
                            <div class="form-buttons"> 
                                <button type="submit" name="action" value="add" id="buttonAdd"><b>Add Book</b></button>
                                <button type="submit" name="action" value="remove" id="buttonRemove"><b>Remove Book</b></button>
                            </div>
                        </form>
                    </div>
                    <div class="box1">
                    <h2 class="header-title">Edit Book Information</h2>
                        <form action="edit_book.php" method="post">
                            <input type="number" name="id" placeholder="Book ID" required>
                            <input type="text" name="title" placeholder="New Title">
                            <input type="text" name="author" placeholder="New Author">
                            <input type="number" name="yearofpublication" placeholder="New Year of Publication">
                            <input type="text" name="genre" placeholder="New Genre">
                            <div class="button-container">
                                 <button type="submit" id="buttonUpdate"><b>Update</b></button>
                            </div>
                        </form>
                    </div>
                </section>
                <section class="section2">
                    <div class="box2">
                        <strong>Library Events</strong> <br>
                        <p>
                            The library hosts a variety of exciting events to immerse visitors in the world of literature. Upcoming events include:
                        </p>
                        <ul style="list-style-type: square;">
                            <li>Westeros Cultural Showcase</li>
                            <li>Interactive Storytelling: Tales from the Seven Kingdoms</li>
                            <li>Medieval Weaponry and Armor Exhibit</li>
                            <li>Fantasy Worldbuilding Workshop</li>
                        </ul>
                    </div>
                    <div class="box2">
                        <strong style="text-align: center;">Library Timings</strong> <br>
                        <p>Our operating hours are as follows:</p>
                        <ul>
                            <li><strong>Friday:</strong> Closed</li>
                            <li><strong>Saturday to Thursday:</strong> 9:00 AM – 5:00 PM</li>
                            <li><strong>Lunch Break:</strong> 12:30 PM – 1:30 PM</li>
                        </ul>
                    </div>
                    <div class="box2">
                        <strong>Books and Related Media</strong> <br>
                        <ul style="list-style-type: square;">
                            <li><strong>Books in the Series:</strong></li>
                            <li>The Rise of Dragons (1990)</li>
                            <li>The War of Kings (1993)</li>
                            <li>Shadows of Winter (1995)</li>
                            <li>The Fall of Thrones (1998)</li>
                            <li>Legends of the Night's Watch (2002)</li>
                            <li>Chronicles of Westeros (2008)</li>
                            <li>Tales of Ice and Fire (2012)</li>
                            <li>Bloodlines of the Realm (2016)</li>
                            <li><strong>Adaptations and Related Media:</strong></li>
                            <li>Legends Reimagined: The Westeros Series (2020–present)</li>
                            <li>The Westeros Chronicles Podcast</li>
                            <li>Illustrated Companions to the Saga</li>
                        </ul>
                    </div>
                </section>

                <section class="section2">
                    <div class="box22a">
                        <form action="process.php" method="post">
                            <b>Student Name</b> 
                            <br><input type="text" placeholder="Student Full Name" name="studentname" id="studentname" required><br>
                            <b>Student ID</b>
                            <br><input type="text" placeholder="Student ID" name="studentid" id="studentID" required><br>
                            <b>Student Email</b>
                            <br><input type="email" placeholder="Student Email" name="email" id="email" required><br>
                            <label for="booktitle"><b>Select A Book : </b></label><br>
                            <select name="booktitle" id="booktitle" required>
                                <option value="Select a Book" disabled selected>Select a Book</option>
                                <option value="Legends of the Fallen Realm">Legends of the Fallen Realm</option>
                                <option value="The Crown of Shadows">The Crown of Shadows</option>
                                <option value="The Eternal Flame">The Eternal Flame</option>
                                <option value="Whispers of the Ancients">Whispers of the Ancients</option>
                                <option value="Chronicles of the Mystic Isles">Chronicles of the Mystic Isles</option>
                                <option value="Blades and Thrones">Blades and Thrones</option>
                                <option value="Echoes of the Forgotten Realm">Echoes of the Forgotten Realm</option>
                                <option value="The Last Heir of Valoria">The Last Heir of Valoria</option>
                                <option value="Oaths of Ice and Fire">Oaths of Ice and Fire</option>
                                <option value="The Shattered Crown">The Shattered Crown</option>
                            </select><br>
                            <b>Borrow date</b>
                            <br><input type="date" name="borrowdate" id="borrowdate" required><br>
                            <b>Return date</b>
                            <br><input type="date" name="returndate" id="returndate" required><br>
                            <b>Token</b>
                            <br><input type="text" placeholder="Token Number" name="token" id="token" required><br>
                            <b>Fees</b>
                            <br><input type="text" placeholder="Fees" name="fees" id="fees" required><br> <br><br>
                            <button type="submit" name="submit" id="button"><b>Submit</b></button>
                        </form>
                    </div>
                    <?php
                    if (file_exists('token.json')) {
                        $tokens_json = file_get_contents('token.json');
                        $tokens = json_decode($tokens_json, true);
                        if ($tokens === null) {
                            echo "Error decoding JSON.";
                        }
                    } else {
                        echo "token.json file not found.";
                    }
                    ?>
                    <div class="box22b">
                        <h3 style="text-align: center;">Available Tokens</h3>
                        <ul>
                        <?php if (isset($tokens) && is_array($tokens)): ?>
                            <?php foreach ($tokens as $token): ?>
                                <?php if (isset($token['token'])): ?>
                                    <button class="token-button">
                                        <strong><?php echo htmlspecialchars($token['token']); ?></strong>
                                    </button><br>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No tokens available or an error occurred while loading the tokens.</p>
                        <?php endif; ?>
                        </ul>
                    </div>
                </section>
            </div>
            <div class="box3">
                <h2 style="text-align: center;">Library Details</h2>
                <img src="Picture/library.jpg" alt="Picture" width="250px" height="250px" style="border-radius: 50%; border: 1px solid black;">
                <hr>
                <Label><b>About</b></Label>
                <p>The Library, established in 1994, supports the academic and research needs of faculty, students, and staff. It has grown significantly, offering a rich collection of over 43,318 books, 1,72,000 e-books, 68,000 e-journals, and resources in various fields like Business, Science, Technology, and Social Sciences. The library operates an open system for AIUB students, allowing book and CD borrowing (excluding textbooks) for seven days using their student ID cards. With a seating capacity for 500+, it uses the "Library System," a software developed in-house, providing modern facilities for efficient library access and management.</p>
                <hr>
                <div class="social-icons">
                    <a href="https://www.facebook.com/aiub.edu" target="_blank">
                        <i class="fab fa-facebook"></i>
                    </a>                  
                    <a href="https://www.linkedin.com/school/aiubedu/" target="_blank">
                        <i class="fab fa-linkedin"></i>
                    </a> 
                </div>

                
            </div>
        </main>
    </body>
    </html>