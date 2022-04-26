create table student (id integer primary key autoincrement, student_name varchar2(50), dept varchar2(10), major_subject varchar2(10), student_id varchar2(50), email varchar2(50) not null, pwdhash binary(64) not null);
CREATE PROCEDURE dbo.addStudent
email VARCHAR2(50),
pwdhash binary(64),
student_name VARCHAR2(50),
dept VARCHAR2(10),
major_subject VARCHAR2(10),
student_id VARCHAR2(50) as concat(id,'_',dept),
responseMessage NVARCHAR(250) OUTPUT
AS
BEGIN
SET NOCOUNT ON
BEGIN TRY
INSERT INTO student values(student_name, dept, major_subject, student_id, email, pwdhash);
SET @responseMessage=' Success.'
END TRY
BEGIN CATCH
SET @responseMessage=ERROR_MESSAGE()
END CATCH
END
