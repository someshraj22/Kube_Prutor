db.createCollection("environments");

/*
  # add languages of your choice. Default languages:
  #  ---, C, CPP, Logo, Python, UNIV
  # NOTES: 
  #   1.) --- is just a placeholder, required so that we can change language
  #       while creating/updating problems (issue with default value in html 
  #       dropdown lists
  #   2.) UNIV is an attemt to create a universal compiler (actually a wrapper)
  #       for few common languages like C, C++, Java, Python
  #   3.) Logo is specific to IIT Bombay and IIT Goa. It requires a
  #       logo-server to be setup separately.
  #   4.) C is the default language (default:true). If you want some other
  #       language to be the default, remove default:true from C, and
  #       insert it for the desired default language.
*/
db.environments.insert([
    { "name" : "---", "editor_mode" : "c_cpp", "compile" : true, "output_format" : "text", "source_ext" : "c", "binary_ext" : "out", "cmd_compile" : "gcc -static -g -o %s.out -Wall %s.c -lm", "cmd_execute" : "%s", "display" : "text", "link_template" : "", "default" : false },
    { "name" : "C", "editor_mode" : "c_cpp", "compile" : true, "output_format" : "text", "source_ext" : "c", "binary_ext" : "out", "cmd_compile" : "gcc -static -g -o %s.out -Wall %s.c -lm", "cmd_execute" : "%s", "display" : "text", "link_template" : "", "default" : true },
    { "name" : "Logo", "editor_mode" : "c_cpp", "compile" : true, "output_format" : "text", "source_ext" : "cpp", "binary_ext" : "out", "cmd_compile" : "/var/www/simplecpp-www/s++ -static -g -o %s.out -Wall %s.cpp", "cmd_execute" : "%s", "display" : "link", "link_template" : "http://cs101.cse.iitb.ac.in:3128/?%s", "default" : false },
    { "name" : "CPP", "editor_mode" : "c_cpp", "compile" : true, "output_format" : "text", "source_ext" : "cpp", "binary_ext" : "out", "cmd_compile" : "g++ -static -g -o %s.out -Wall %s.cpp", "cmd_execute" : "%s", "display" : "text", "link_template" : "", "default" : false },
    { "name" : "Python", "editor_mode" : "python", "compile" : true, "output_format" : "text", "source_ext" : "py", "binary_ext" : "out", "cmd_compile" : "/var/www/app/compilers/unicomp/python.sh %s.py", "cmd_execute" : "%s", "display" : "text", "link_template" : "", "default" : false },
    { "name" : "UNIV" }
])

