echo "****** Création de paramètre.sh *****"

txt="parameters:\n\n"
driver="pdo_mysql"

while getopts ":-:" option
do
echo $OPTARG | while IFS="=" read option arg
    do
        case "$option" in
            env)
                case "$arg" in
                    home)
                        host="127.0.0.1"
                        port="~"
                        name="mimizik"
                        user="root"
                        password="~"
                    ;;
                    work)
                        host="localhost"
                        port="8889"
                        name="mimizik"
                        user="root"
                        password="root"
                    ;;
                    *)
                        echo "env non défini"
                    ;;
                esac

            ;;
            test)
                echo "c'est bien test \n en bas \n"
            ;;
            *)
                echo -e "Mauvais argument"
            ;;
        esac

        txt="$txt
        \tdatabase_driver:\t$driver\n
        \tdatabase_host:\t"$host"\n
        \tdatabase_port:\t$port\n
        \tdatabase_name:\t$name\n
        \tdatabase_user:\t$user\n
        \tdatabase_password:\t$password\n\n
        \tmailer_transport:\tsmtp\n
        \tmailer_host:\t'mail.mimizik.com'\n
        \tmailer_user:\t'franchesco971@mimizik.com'\n
        \tmailer_password:\t'franches971'\n
        \tmailer_port:\t465\n\n
        \tlocale:\tfr\n
        \tsecret:\tThisTokenIsNotSoSecretChangeIt"

        echo $txt > app/config/parameters.yml
    done
done

