# Check unique problem (category,id) for a given event
SELECT * FROM assignment as a, problem as p, `event` as e where a.problem_id=p.id and a.event_id = e.id and e.name like '%Lab Exam 1%' group by p.category, p.id_problem

# Marks csv
SELECT ac.roll,sum(g.marks) FROM `grade` as g, assignment as a, event as e, account as ac 
where g.assignment_id=a.id and a.event_id = e.id and e.name like '%LAB 6%' and user_id=ac.id and ac.roll < 99999
group by ac.roll
ORDER BY sum(g.marks), ac.roll ASC

# ------------------------------------

# Import problems from old DB to new
INSERT INTO its.problem(category,id_p,id_v,id_d,statement,solution,template)
SELECT  "LAB-3 (SERIES)", p.id_problem, p.id_version, p.id_difficulty, p.statement, p.solution, p.template
FROM its_temp.problem AS p
WHERE p.category =  "SERIES (LAB-3)" and p.id_difficulty=1

# Import testcases from old DB to new
insert into its.test_case (problem_id,input,output,visibility,type) 
SELECT newp.id,t.input,t.output,t.visibility,t.type 
FROM its_temp.testcase as t,its_temp.problem as p, its.problem as newp 
where t.problem_id=p.id and p.category="LAB-4 (PATTERN)" and p.solution=newp.solution

# ------------------------------------

# Import lab problems as new practice problems
INSERT INTO  `problem` (  `title` ,  `category` ,  `statement` ,  `solution` ,  `template` ,  `id_p` ,  `id_v` ,  `id_d` ) 
SELECT title,  'Practice-3.5 (SERIES LAB)', statement, solution, template, id_p, id_v, id_d
FROM  `problem` 
WHERE category LIKE  "LAB-3 (SERIES)"

# Import testcases from lab problems to corres. practice problems
insert into test_case (problem_id,input,output,visibility,type) 
SELECT newp.id,t.input,t.output,t.visibility,t.type 
FROM test_case as t,problem as p, problem as newp 
where t.problem_id=p.id and p.category like "LAB-2%" and newp.category like "practice-2.5%" and p.id<>newp.id and p.solution=newp.solution

# ------------------------------------

# Problem Scheduling - Insert new assignment entries
INSERT INTO `assignment`(`user_id`, `event_id`, `event_name`, `question`, `problem_id`, `max_marks`, `score`, `comments`, `evaluator`, `grading_time`, `is_submitted`, `submission`) 
select ac.id, e.id, e.name, p.id_v, p.id, 20, NULL, "", NULL, NULL, 0, NULL from account as ac, problem as p, event as e
where ac.section='TA' and p.category="MockLabExam-1" and e.id=83

# Problem Scheduling - Insert new code entries
INSERT INTO `code`(`assignment_id`, `user_id`, `contents`, `save_type`) 
select a.id, a.user_id, p.template, 0 from assignment as a, problem as p, account as ac 
where a.user_id=ac.id and ac.section='TA' and a.problem_id=p.id and p.category="MockLabExam-1"  and a.event_id=83 

# ------------------------------------
# Lab-Exam grading, select student LabExam assignments and TA name
SELECT a.*, ac2.name 
FROM `assignment` as a, account as ac1, account as ac2 
WHERE a.user_id=ac1.id and ac1.section rlike 'A[0-9]+' and event_id=86 and ac2.id=a.evaluator 

# Mysql Dump into CSV - graded assignments
mysql -B -P 3306 -h 172.17.0.93 -u dbadmin -p its -e "SELECT a.* FROM assignment as a, account as ac1 WHERE a.user_id=ac1.id and ac1.section rlike 'A[0-9]+' and event_id=134 " |sed "s/'/\'/;s/\t/\",\"/g;s/^/\"/;s/$/\"/;s/\n//g" > outfile.csv

# MySQL Dump
mysqldump -P 3306 -h 172.17.0.93 -u dbadmin -p its > its.sql

