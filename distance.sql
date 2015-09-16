CREATE OR REPLACE FUNCTION gis_distance(point, point)
  RETURNS double precision AS
$BODY$
SELECT 2 * R * ASIN( d / 2 / R )
FROM (
  SELECT SQRT((x1 - x2)^2 + (y1 - y2)^2 + (z1 - z2)^2) AS d, R
  FROM (
    SELECT c.R
         , c.R * COS(pi() * l1.lat/180) * COS(pi() * l1.lng/180) AS x1
         , c.R * COS(pi() * l1.lat/180) * SIN(pi() * l1.lng/180) AS y1
         , c.R * SIN(pi() * l1.lat/180)                          AS z1
         , c.R * COS(pi() * l2.lat/180) * COS(pi() * l2.lng/180) AS x2
         , c.R * COS(pi() * l2.lat/180) * SIN(pi() * l2.lng/180) AS y2
         , c.R * SIN(pi() * l2.lat/180)                          AS z2
    FROM (SELECT $1[0] AS lat, $1[1] AS lng) AS l1
       , (SELECT $2[0] AS lat, $2[1] AS lng) AS l2
       , (SELECT 6378.137 AS R) AS c
  ) trig
) sq
$BODY$
  LANGUAGE sql;

DROP TABLE IF EXISTS university;
CREATE TABLE university(
    name varchar(255),
    address varchar(255),
    location point
);
INSERT INTO university VALUES('Jyväskylä University', 'Seminaarinkatu 15, 40014 Jyväskylän yliopisto, Finland', '(62, 25)');
INSERT INTO university VALUES('University of Helsinki', 'Yliopistonkatu 4, 00100 Helsinki, Finland', '(60, 24)');
INSERT INTO university VALUES('University of Twente', 'Drienerlolaan 5, 7522 NB Enschede, Netherlands', '(52, 6)');
INSERT INTO university VALUES('University of Cambridge', 'University Of Cambridge, Cambridge, Cambridge, Cambridgeshire CB2, UK', '(52, 0)');
INSERT INTO university VALUES('Keio University', '36 Yoshidahonmachi, Sakyō-ku, Kyōto-shi, Kyōto-fu 606-8317, Japan', '(35, 135)');
INSERT INTO university VALUES('University Stuttgart', 'Keplerstraße 7, 70174 Stuttgart, Germany', '(48, 9)');
INSERT INTO university VALUES('California Institute of Technology', '1200 E California Blvd, Pasadena, CA 91125, United States', '(34, -118)');
INSERT INTO university VALUES('Massachusetts Institute of Technology', '77 Massachusetts Ave, Cambridge, MA 02139, United States', '(47, -71)');

INSERT INTO university VALUES('Imperial College', '77 Massachusetts Ave, Cambridge, MA 02139, United States', '(51, 0)');
INSERT INTO university VALUES('Harvard University', 'Cambridge, MA 02138, United States', '(42, -71)');
INSERT INTO university VALUES('University of Oxford', 'Oxford, United Kingdom', '(51, -1)');
INSERT INTO university VALUES('National University of Singapore', '21 Lower Kent Ridge Rd, Singapore 119077', '(1, 103)');

INSERT INTO university VALUES('University of Amsterdam', '1012 WX Amsterdam, Netherlands', '(52, 4)');
INSERT INTO university VALUES('Technische Universität München', 'Arcisstraße 21, 80333 München, Germany', '(48, 11)');
INSERT INTO university VALUES('University of Kansas', '1450 Jayhawk Blvd, Lawrence, KS 66045, United States', '(38, -95)');

/*
SELECT c2.*, gis_distance(c1.location, c2.location) AS distance
FROM university c1, university c2
WHERE c1.name = 'Jyväskylä University'
ORDER BY distance ASC;
*/