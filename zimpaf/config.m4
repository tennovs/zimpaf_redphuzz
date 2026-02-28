
dnl Copyright 2026 Tennov Simanjuntak, The University of Texas at Arlington
dnl
dnl Licensed under the Apache License, Version 2.0 (the "License");
dnl you may not use this file except in compliance with the License.
dnl You may obtain a copy of the License at
dnl
dnl     http://www.apache.org/licenses/LICENSE-2.0
dnl
dnl Unless required by applicable law or agreed to in writing, software
dnl distributed under the License is distributed on an "AS IS" BASIS,
dnl WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
dnl See the License for the specific language governing permissions and
dnl limitations under the License.


dnl config.m4 for extension zimpaf

dnl Comments in this file start with the string 'dnl'.
dnl Remove where necessary.

dnl If your extension references something external, use 'with':

dnl PHP_ARG_WITH([zimpaf],
dnl   [for zimpaf support],
dnl   [AS_HELP_STRING([--with-zimpaf],
dnl     [Include zimpaf support])])

dnl Otherwise use 'enable':

PHP_ARG_ENABLE([zimpaf],
  [whether to enable zimpaf support],
  [AS_HELP_STRING([--enable-zimpaf],
    [Enable zimpaf support])],
  [no])

if test "$PHP_ZIMPAF" != "no"; then
  dnl Write more examples of tests here...

  dnl Remove this code block if the library does not support pkg-config.
  dnl PKG_CHECK_MODULES([LIBFOO], [foo])
  dnl PHP_EVAL_INCLINE($LIBFOO_CFLAGS)
  dnl PHP_EVAL_LIBLINE($LIBFOO_LIBS, ZIMPAF_SHARED_LIBADD)

  dnl If you need to check for a particular library version using PKG_CHECK_MODULES,
  dnl you can use comparison operators. For example:
  dnl PKG_CHECK_MODULES([LIBFOO], [foo >= 1.2.3])
  dnl PKG_CHECK_MODULES([LIBFOO], [foo < 3.4])
  dnl PKG_CHECK_MODULES([LIBFOO], [foo = 1.2.3])

  dnl Remove this code block if the library supports pkg-config.
  dnl --with-zimpaf -> check with-path
  dnl SEARCH_PATH="/usr/local /usr"     # you might want to change this
  dnl SEARCH_FOR="/include/zimpaf.h"  # you most likely want to change this
  dnl if test -r $PHP_ZIMPAF/$SEARCH_FOR; then # path given as parameter
  dnl   ZIMPAF_DIR=$PHP_ZIMPAF
  dnl else # search default path list
  dnl   AC_MSG_CHECKING([for zimpaf files in default path])
  dnl   for i in $SEARCH_PATH ; do
  dnl     if test -r $i/$SEARCH_FOR; then
  dnl       ZIMPAF_DIR=$i
  dnl       AC_MSG_RESULT(found in $i)
  dnl     fi
  dnl   done
  dnl fi
  dnl
  dnl if test -z "$ZIMPAF_DIR"; then
  dnl   AC_MSG_RESULT([not found])
  dnl   AC_MSG_ERROR([Please reinstall the zimpaf distribution])
  dnl fi

  dnl Remove this code block if the library supports pkg-config.
  dnl --with-zimpaf -> add include path
  dnl PHP_ADD_INCLUDE($ZIMPAF_DIR/include)

  dnl Remove this code block if the library supports pkg-config.
  dnl --with-zimpaf -> check for lib and symbol presence
  dnl LIBNAME=ZIMPAF # you may want to change this
  dnl LIBSYMBOL=ZIMPAF # you most likely want to change this

  dnl If you need to check for a particular library function (e.g. a conditional
  dnl or version-dependent feature) and you are using pkg-config:
  dnl PHP_CHECK_LIBRARY($LIBNAME, $LIBSYMBOL,
  dnl [
  dnl   AC_DEFINE(HAVE_ZIMPAF_FEATURE, 1, [ ])
  dnl ],[
  dnl   AC_MSG_ERROR([FEATURE not supported by your zimpaf library.])
  dnl ], [
  dnl   $LIBFOO_LIBS
  dnl ])

  dnl If you need to check for a particular library function (e.g. a conditional
  dnl or version-dependent feature) and you are not using pkg-config:
  dnl PHP_CHECK_LIBRARY($LIBNAME, $LIBSYMBOL,
  dnl [
  dnl   PHP_ADD_LIBRARY_WITH_PATH($LIBNAME, $ZIMPAF_DIR/$PHP_LIBDIR, ZIMPAF_SHARED_LIBADD)
  dnl   AC_DEFINE(HAVE_ZIMPAF_FEATURE, 1, [ ])
  dnl ],[
  dnl   AC_MSG_ERROR([FEATURE not supported by your zimpaf library.])
  dnl ],[
  dnl   -L$ZIMPAF_DIR/$PHP_LIBDIR -lm
  dnl ])
  dnl
  dnl PHP_SUBST(ZIMPAF_SHARED_LIBADD)

  dnl In case of no dependencies
  AC_DEFINE(HAVE_ZIMPAF, 1, [ Have zimpaf support ])

  PHP_CPPFLAGS="$PHP_CPPFLAGS -DHAVE_CONFIG_H"
  PHP_CFLAGS="$PHP_CFLAGS -DZEND_COMPILE_DL_EXT=1 -fvisibility=default"

  if test "$PHP_ZTS" = "yes"; then
    PHP_CFLAGS="$PHP_CFLAGS -DZTS"
  fi

  AC_DEFINE(COMPILE_DL_ZIMPAF, 1, [Compile as dynamic library])

  PHP_NEW_EXTENSION(zimpaf, zimpaf.c request_retrieval.c libhooks/dbhook.c \
                                libhooks/sanithook.c libhooks/deserhook.c libhooks/xxehook.c \
                                libhooks/codexechook.c libhooks/dirtravshook.c libcjson/cJSON.c \
                                libhooks/error_exception_hook.c utils/utils.c, $ext_shared)
fi
