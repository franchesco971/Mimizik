echo "****** Creation de parametre.sh *****"

txt="parameters:"
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

    database_driver:    $driver
    database_host:      $host
    database_port:      $port
    database_name:      $name
    database_user:      $user
    database_password:  $password

    mailer_transport:   smtp
    mailer_host:        'mail.mimizik.com'
    mailer_user:        'franchesco971@mimizik.com'
    mailer_password:    'franches971'
    mailer_port:        465

    locale:             fr
    secret:             ThisTokenIsNotSoSecretChangeIt"
        
        echo "$txt" > app/config/parameters.yml
    done
done

