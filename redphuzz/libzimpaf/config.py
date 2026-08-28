def get_db_config(http_target): #c is the candidate/input object
    HOST    = 'db_host'
    if 'poc' in http_target.lower():
        return HOST, 'all_db_user', 'password', 'simple_db'
    elif 'dvwa' in http_target.lower():
        return HOST, 'all_db_user', 'password', 'dvwa'
    elif 'bwapp' in http_target.lower():
        return HOST, 'all_db_user', 'password', 'bWAPP'
    elif 'xvwa' in http_target.lower():
        return HOST, 'all_db_user', 'password', 'xvwa'
    elif 'wordpress' in http_target.lower():
        return HOST, 'all_db_user', 'password', 'wordpress'
    else:
        return HOST, 'all_db_user', 'password', 'wackopicko'

# USER    = 'simple_db_user'
# PASSWD  = 'password'
# DB      = 'simple_db'



# USER    = 'bwapp_user'
# PASSWD  = 'password'
# DB      = 'bWAPP'

# USER    = 'wackopicko'
# PASSWD  = 'webvuln!@#'
# DB      = 'wackopicko'

# USER    = 'xvwa_user'
# PASSWD  = 'password'
# DB      = 'xvwa'

#wordpress
# USER    = 'wordpress_user'
# PASSWD  = 'password'
# DB      = 'wordpress'
