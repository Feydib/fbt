-- 1/8
INSERT INTO FBT_MatchPrerequisite (idMatchs, idPoolWinner, idPoolSecond) VALUES (49,1,2);
INSERT INTO FBT_MatchPrerequisite (idMatchs, idPoolWinner, idPoolSecond) VALUES (50,3,4);
INSERT INTO FBT_MatchPrerequisite(idMatchs, idPoolWinner, idPoolSecond)VALUES (51,2,1);
INSERT INTO FBT_MatchPrerequisite (idMatchs, idPoolWinner, idPoolSecond) VALUES (52,4,3);
INSERT INTO FBT_MatchPrerequisite(idMatchs, idPoolWinner, idPoolSecond)VALUES (53,5,6);
INSERT INTO FBT_MatchPrerequisite (idMatchs, idPoolWinner, idPoolSecond) VALUES (54,7,8);
INSERT INTO FBT_MatchPrerequisite (idMatchs, idPoolWinner, idPoolSecond)VALUES (55,6,5);
INSERT INTO FBT_MatchPrerequisite (idMatchs, idPoolWinner, idPoolSecond) VALUES (56,8,7);
-- 1/4
INSERT INTO FBT_MatchPrerequisite (idMatchs, idMatchs1, idMatchs2) VALUES (57, 53, 54);
INSERT INTO FBT_MatchPrerequisite (idMatchs, idMatchs1, idMatchs2) VALUES (58, 49, 50);
INSERT INTO FBT_MatchPrerequisite (idMatchs, idMatchs1, idMatchs2) VALUES (59, 55, 56);
INSERT INTO FBT_MatchPrerequisite (idMatchs, idMatchs1, idMatchs2) VALUES (60, 51, 52);
-- 1/2
INSERT INTO FBT_MatchPrerequisite (idMatchs, idMatchs1, idMatchs2) VALUES (61, 57, 58);
INSERT INTO FBT_MatchPrerequisite (idMatchs, idMatchs1, idMatchs2) VALUES (62, 59, 60);

-- final looser 

--final
INSERT INTO FBT_MatchPrerequisite (idMatchs, idMatchs1, idMatchs2) VALUES (64, 61, 62);
