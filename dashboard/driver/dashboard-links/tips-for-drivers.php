<?php
$pageConfig = [
    'title' => 'Tips for Drivers',
    'styles' => ["../../dashboard.css", "./driver-dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
require_once "../../../db/connect.php";


if ($_SESSION['user']['role'] !== 'driver') {
    die("unauthorized user!");
}

$driverId = $_SESSION['user']['id'] ?? null;

if (!$driverId) {
    die("Unauthorized access.");
}
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container large">
                <h2 style="margin-bottom:12px;">15 driving test tips to help you pass first time</h2>
                <img src="../../../assets/dr1.jpg" alt="">
                <div>
                    <h3>Ease those learning to drive nerves with our top tips to help you pass your driving test first
                        time.</h3>
                    <p>
                        <span>Each year, there are around 1.6 million driving tests in England, Scotland and Wales; yet,
                            according to official statistics, more than half of all learners will fail. <br></span>
                        <br><span style="margin-bottom: 8px;">You can avoid becoming part of this statistic by ensuring
                            you are as prepared as you can possibly be for the practical test. <br></span>
                        <br>That's why we have put together the top 15 expert driving test tips to help you pass on the
                        big day.
                    </p>
                    <br>
                    <h3>How to pass your driving test quickly</h3>
                    <h4>1. Be on time</h4>
                    <p>
                        It's an obvious one to start with but turning up in good time for your test will start you off
                        on the right foot. <br><br>
                        Arriving late puts you at risk of missing it entirely, while rushing to get there in time will
                        leave you feeling flustered, even if you do make it. <br><br>
                        Arrive at your test centre 10-20 minutes beforehand so you have long enough to prepare, but
                        won’t be waiting around too long. <br><br>
                        Ensuring you get a good night’s sleep is also important to avoid unnecessary stress or anxiety.
                    </p>
                    <br>
                    <h4>2. Have a lesson beforehand</h4>
                    <p>
                        We’d also recommend fitting in a driving lesson on the day of your test if possible – that way
                        you can go over any manoeuvres or ask for clarification on last-minute questions you may have.
                        <br><br>
                        A lesson beforehand will help calm your nerves and put you in the right frame of mind for
                        driving, especially if you have been receiving two-hour lessons in the weeks building up to your
                        test, which we'd also recommend. <br>
                    </p>
                    <br>
                    <ul>
                        <li>Road crossings - do you know your pelicans from your toucans?</li>
                        <li>Young drivers warned over fake insurance policies</li>
                        <li>Did you know that we offer specialist learner driver insurance?</li>
                    </ul>
                    <br>
                    <h4>3. Check you have everything you need</h4>
                    <p>
                        Thousands of driving tests each year don’t go ahead because the candidate fails to turn up with
                        everything needed on the day. <br><br>
                        Make sure you have all the required documents and that your car is properly equipped and up to
                        the test standard. <br><br>
                        You can double check what you need to take with the RAC's how to pass your driving test guide.
                        <br><br>
                    </p>
                    <br><br>
                    <h4>4. Use your instructor’s car</h4>
                    <p>
                        Being in a car you know well and feel comfortable in can maximise the chances of passing your
                        driving test first time. <br><br>
                        Not only will it definitely be up to the examiners’ standard (there are certain requirements
                        like having additional mirrors that test cars have to meet) but you’ll also have an advantage
                        when it comes to the ‘Show Me, Tell Me’ section of the test – knowing precisely where and how to
                        activate controls such as the air-con or fog lights, for example. <br><br>
                        Ask your instructor to talk you through the mechanics of the car as many times as you need. This
                        will help you to sail through the beginning part of your test, so you can start it off feeling
                        confident before you’ve even got out on the road.
                    </p>
                    <br>
                    <h4>5. Take your instructor along for reassurance</h4>
                    <p>
                        It’s by no means compulsory to take anyone along with you but be aware you have the option to
                        take your instructor in the car for the duration of the test. It may put you at ease and help
                        you to feel more comfortable. <br><br>
                        They’ll also provide another pair of eyes – so if you do happen to fail, they’ll have additional
                        constructive feedback. In fact, you can take anyone you want along for reassurance, providing
                        they are over 16. <br>
                    </p>
                    <br>
                    <h4>6. Ask your examiner to repeat, if you need</h4>
                    <p>
                        If you don’t hear an instruction properly during your test, stay calm and just ask the examiner
                        to repeat it. Panicking will only cause you to lose focus and slip up. <br>
                    </p>
                    <br>
                    <h4>7. Don’t assume you’ve failed</h4>
                    <p>
                        One of the most important tips to pass your driving test is to never assume you've already
                        failed. If you do make a mistake, remember you’re allowed up to 15 minors during your test so
                        try not to dwell on them and always assume you’re still going to pass. <br><br>
                        Don’t let minor mistakes play on your mind, or you run the risk of making even more. <br>
                    </p>
                    <br>
                    <h4>8. Choose where you want to take your test</h4>
                    <!-- <img src="../../../assets/dr2.jpg" alt="" style="width:auto;"> -->
                    <p>
                        It’s natural that driving test centres located in congested areas with lots of complicated
                        roundabouts have lower pass rates than those in rural areas with nothing but a few tractors and
                        stray livestock to worry about. <br><br>
                        While taking your test on the Isle of Mull - where there’s a pass rate of more than 90% - is
                        unreasonable for most of us, compare the test pass rates of your local test centres. <br><br>
                        It’s not cheating to take your test somewhere with a higher pass rate - but do ask yourself
                        whether doing so will properly prepare you for driving after taking the test. <br>
                    </p>
                    <br>
                    <h4>9. Get to know your test routes</h4>
                    <p>
                        Once you’ve selected your test centre, you can always get to know the area and test routes
                        beforehand. <br><br>
                        Make sure you’ve practiced on a variety of roads. A mixture of major and minor roads, country
                        lanes and dual carriageways is important if you want to avoid any nasty surprises on test day.
                        <br>
                    </p>
                    <br>
                    <h4>10. Exaggerate those mirror checks</h4>
                    <!-- <img src="../../../assets/dr3.jpg" alt=""> -->

                    <p>
                        One of the biggest causes of minor faults for many learner drivers in their test is a lack of
                        observation. <br><br>
                        Check your mirrors regularly - especially when setting off, approaching hazards, changing road
                        position and changing gears. Move your head when checking your mirrors and your examiner is less
                        likely to give you a minor fault than if you give the mirror a quick glance. <br>
                    </p>
                </div>
            </div>

        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>