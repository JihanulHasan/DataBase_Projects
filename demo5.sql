CREATE TABLE STUDENT (
    stdid number(5),
    stdname varchar2(20),
    dob date,
    doj date,
    fee number(5),
    gender char
);
INSERT INTO STUDENT (
    stdid, stdname, dob, doj, fee, gender
)
VALUES (1, 'SHAREEF', '20-jan-2001', '25-oct-2001', 10000, 'M');
INSERT INTO STUDENT (
    stdid, stdname, dob, doj, fee, gender
)
VALUES (2, 'NADEEM', '17-nov-2019', '26-oct-2001', 11000, 'M');
