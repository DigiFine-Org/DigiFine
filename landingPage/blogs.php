<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs - Sri Lanka Police | DigiFine</title>
    <link rel="stylesheet" href="landing-page.css">
    <style>
        .blogs-section {
            max-width: 1000px;
            margin: 60px auto;
            padding: 0 20px;
        }
        
        .blogs-section h1 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }
        
        .blog-posts {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .blog-post {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .blog-post:hover {
            transform: translateY(-5px);
        }
        
        .blog-post img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .blog-content {
            padding: 20px;
        }
        
        .blog-content h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #333;
        }
        
        .blog-content p {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .read-more {
            display: inline-block;
            color: #1e77ca;
            text-decoration: none;
            font-weight: 500;
        }
        
        .read-more:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?php
    include_once 'navbar.php';
    ?>

    <main>
        <section class="blogs-section">
            <h1>Latest Blog Posts</h1>
            <div class="blog-posts">
                <div class="blog-post">
                    <img src="../assets/blog1.jpg" alt="Road Safety">
                    <div class="blog-content">
                        <h3>Road Safety Measures for Drivers</h3>
                        <p>Learn about the latest road safety measures and how to keep yourself and others safe while driving on Sri Lanka's roads.</p>
                        <a href="#" class="read-more">Read More</a>
                    </div>
                </div>
                <div class="blog-post">
                    <img src="../assets/blog2.jpg" alt="Traffic Laws">
                    <div class="blog-content">
                        <h3>Understanding Traffic Laws</h3>
                        <p>A comprehensive guide to understanding traffic laws in Sri Lanka and how they help maintain order on the roads.</p>
                        <a href="#" class="read-more">Read More</a>
                    </div>
                </div>
                <div class="blog-post">
                    <img src="../assets/blog3.jpg" alt="Digital Payments">
                    <div class="blog-content">
                        <h3>Benefits of Digital Fine Payments</h3>
                        <p>Discover how digital fine payments are making it easier for citizens to comply with traffic regulations.</p>
                        <a href="#" class="read-more">Read More</a>
                    </div>
                </div>
                <div class="blog-post">
                    <img src="../assets/blog4.jpg" alt="Police Technology">
                    <div class="blog-content">
                        <h3>Technology in Modern Policing</h3>
                        <p>Learn how the Sri Lanka Police is leveraging technology to improve service delivery and ensure public safety.</p>
                        <a href="#" class="read-more">Read More</a>
                    </div>
                </div>
                <div class="blog-post">
                    <img src="../assets/blog5.jpg" alt="Community Policing">
                    <div class="blog-content">
                        <h3>Community Policing Initiatives</h3>
                        <p>Explore the various community policing initiatives being implemented to foster better police-community relations.</p>
                        <a href="#" class="read-more">Read More</a>
                    </div>
                </div>
                <div class="blog-post">
                    <img src="../assets/blog6.jpg" alt="Traffic Management">
                    <div class="blog-content">
                        <h3>Effective Traffic Management</h3>
                        <p>An inside look at how traffic is managed in busy urban areas and what measures are being taken to reduce congestion.</p>
                        <a href="#" class="read-more">Read More</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php
    include_once 'footer.php';
    ?>
</body>

</html>