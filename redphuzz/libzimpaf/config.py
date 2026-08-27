def get_db_config(http_target): #c is the candidate/input object
    HOST    = 'localhost'
    if 'orders' in http_target.lower():
        return HOST, 'simple_db_user', 'password', 'simple_db'
    elif 'dvwa' in http_target.lower():
        return HOST, 'dvwa_user', 'password', 'dvwa'
    elif 'bwapp' in http_target.lower():
        return HOST, 'bwapp_user', 'password', 'bWAPP'
    elif 'wackopicko' in http_target.lower():
        return HOST, 'wackopicko', 'webvuln!@#', 'wackopicko'
    elif 'xvwa' in http_target.lower():
        return HOST, 'xvwa_user', 'password', 'xvwa'
    elif 'wordpress' in http_target.lower():
        return HOST, 'wordpress_user', 'password', 'wordpress'

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