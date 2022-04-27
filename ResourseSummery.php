<?php

$semester = isset($_POST["semester"]) ? $_POST["semester"] : "";
$semester2 = isset($_POST["semester"]) ? $_POST["semester"] : "Semester";
$year = isset($_POST["year"]) ? $_POST["year"] : "";

$con = new mysqli('localhost', 'root', '', 'dbms');

$query = $con->query("
SELECT
    CASE
          WHEN Session = 'Spring' THEN 'Spring'
          WHEN Session = 'Summer' THEN 'Summer'
          WHEN Session = 'Autumn' THEN 'Autumn'
      END AS 'School',
      SUM(Enrolled) AS 'Sum',
      SUM(Enrolled)/COUNT(section_t.Course_ID) AS 'Avg Enroll',
      SUM(room_t.Capacity)/COUNT(section_t.Course_ID) AS 'Avg Room',
      (SUM(room_t.Capacity)/COUNT(section_t.Course_ID) - SUM(Enrolled)/COUNT(section_t.Course_ID)) AS 'Difference',
      ((SUM(room_t.Capacity)/COUNT(section_t.Course_ID) - SUM(Enrolled)/COUNT(section_t.Course_ID))/(SUM(room_t.Capacity)/COUNT(section_t.Course_ID)))*100 AS 'Unused %'
      FROM section_t, room_t, course_t
      WHERE Session = '$semester' 
      AND YEAR = '$year' 
      AND section_t.Room_ID = room_t.Room_Id 
      AND section_t.Course_ID = course_t.Course_ID AND section_t.Blocked!='B-0'
      UNION
      SELECT 
      CASE
      WHEN course_t.School_Title='SBE' then 'SBE'
      WHEN course_t.School_Title='SELS' then 'SELS'
      WHEN course_t.School_Title='SETS' then 'SETS'
      WHEN course_t.School_Title='SLASS' then 'SLASS'
      WHEN course_t.School_Title='SPPH' then 'SPPH'
      END AS 'School',
      SUM(Enrolled) AS 'Sum',
      SUM(Enrolled)/COUNT(section_t.Course_ID) AS 'Avg Enroll',
      SUM(room_t.Capacity)/COUNT(section_t.Course_ID) AS 'Avg Room',
      (SUM(room_t.Capacity)/COUNT(section_t.Course_ID) - SUM(Enrolled)/COUNT(section_t.Course_ID)) AS 'Difference',
      ((SUM(room_t.Capacity)/COUNT(section_t.Course_ID) - SUM(Enrolled)/COUNT(section_t.Course_ID))/(SUM(room_t.Capacity)/COUNT(section_t.Course_ID)))*100 AS 'Unused %'
      FROM section_t, room_t, course_t
      WHERE Session = '$semester' 
      AND YEAR = '$year' 
      AND section_t.Room_ID = room_t.Room_Id
      AND section_t.Course_ID = course_t.Course_ID AND section_t.Blocked!='B-0'
      GROUP BY School;
  ");


$query2 = $con->query("
  SELECT 'Average of ROOM_CAPACITY' as 'Average Capacities', 
  SUM(room_t.Capacity)/COUNT(section_t.Course_ID) as $semester2 
  FROM section_t, course_t, room_t 
  WHERE Session= '$semester' 
  AND YEAR = '$year' 
  AND section_t.Course_ID=course_t.Course_ID 
  AND section_t.Room_ID=room_t.Room_Id 
  AND section_t.Blocked!='B-0' 
  UNION 
  SELECT 'Average of ENROLLED', 
  SUM(Enrolled)/COUNT(section_t.Course_ID) as 'Avg Enrolled' 
  FROM section_t, course_t, room_t 
  WHERE Session= '$semester' 
  AND YEAR = '$year' 
  AND section_t.Course_ID=course_t.Course_ID 
  AND section_t.Room_ID=room_t.Room_Id 
  AND section_t.Blocked!='B-0'
  UNION 
  SELECT 'Average of Free Space', 
  (SUM(room_t.Capacity)/COUNT(section_t.Course_ID) - SUM(Enrolled)/COUNT(section_t.Course_ID)) as 'Average of Free Space'  
  FROM section_t, course_t, room_t 
  WHERE Session= '$semester' 
  AND YEAR = '$year' 
  AND section_t.Course_ID=course_t.Course_ID 
  AND section_t.Room_ID=room_t.Room_Id 
  AND section_t.Blocked!='B-0' 
  UNION
  SELECT 'Free Percent', 
((SUM(room_t.Capacity)/COUNT(section_t.Course_ID) - SUM(Enrolled)/COUNT(section_t.Course_ID))/(SUM(room_t.Capacity)/COUNT(section_t.Course_ID)))*100 as 'Unsed%' 
FROM section_t, course_t, room_t
WHERE Session = '$semester' 
AND YEAR = '$year' 
AND section_t.Course_ID=course_t.Course_ID 
AND section_t.Room_ID=room_t.Room_Id 
AND section_t.Blocked!='B-0' 
  ");







?>



<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Bebas+Neue&family=Karla:wght@200;300;400&family=Oswald:wght@200;300;400;500;600;700&family=Righteous&display=swap" rel="stylesheet">
    <title> Student Enrollment Analysis System</title>
</head>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="main_contents">
            <div class="row mt-5">
                <div class=" d-flex align-items-center justify-content-center">
                    <form method="POST">
                        <div class="">
                            <select class="dropDown_bar shadow me-5" name="semester" id="semester">

                                <option value="Summer">Summer</option>
                                <option value="Spring">Spring</option>
                                <option value="Autumn">Autumn</option>
                            </select>
                            <select class="dropDown_bar shadow me-5" name="year" id="year">

                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                            </select>
                            <button class="btn btn-primary shadow-lg rounded-pill" type="submit"> submit </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- first table -->
            <div class="row mt-5 ps-5">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <h4 class="text-primary fw-bolder lh-lg pt-5 pb-3 text-uppercase"> resource summery in <?php echo $semester2 . " " . $year ?></h4>
                    <table class="table table-striped table-info">
                        <thead>
                            <tr>

                                <?php

                                while ($fieldinfo = $query->fetch_field()) {
                                    echo '<th scope="col">' . $fieldinfo->name . '</th>';
                                }

                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($query->num_rows > 0) {
                                while ($row = $query->fetch_row()) {
                                    echo '<tr>';
                                    for ($i = 0; $i < sizeof($row); $i++) {

                                        echo '<td>' . $row[$i] . '</td>';
                                    }
                                    echo '</tr>';
                                }
                            } else {
                                echo "0 results";
                            }

                            ?>

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- second table -->
            <div class="row mt-5 ps-5">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <h4 class="text-primary fw-bolder lh-lg pt-5 pb-3 text-uppercase"> resource summery in <?php echo $semester2 . " " . $year ?></h4>
                    <table class="table table-striped table-info">
                        <thead>
                            <tr>

                                <?php

                                while ($fieldinfo = $query2->fetch_field()) {
                                    echo '<th scope="col">' . $fieldinfo->name . '</th>';
                                }

                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($query->num_rows > 0) {
                                while ($row = $query2->fetch_row()) {
                                    echo '<tr>';
                                    for ($i = 0; $i < sizeof($row); $i++) {

                                        echo '<td>' . $row[$i] . '</td>';
                                    }
                                    echo '</tr>';
                                }
                            } else {
                                echo "0 results";
                            }

                            mysqli_close($con);
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->


   
</body>

</html>