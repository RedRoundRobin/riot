#!/bin/bash
echo '
   ___         __  ___                    __  ___       __   _    
  / _ \___ ___/ / / _ \___  __ _____  ___/ / / _ \___  / /  (_)__ 
 / , _/ -_) _  / / , _/ _ \/ // / _ \/ _  / / , _/ _ \/ _ \/ / _ \
/_/|_|\__/\_,_/ /_/|_|\___/\_,_/_//_/\_,_/ /_/|_|\___/_.__/_/_//_/
                                                                  
'
echo 'Fixing code style'


npm run prettier-eslint
npm run prettier-eslint-test
vendor/bin/phpcbf -n
vendor/bin/php-cs-fixer fix --config=.php_cs.php --diff -vvv