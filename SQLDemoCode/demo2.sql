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
