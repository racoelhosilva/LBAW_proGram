mkdir -p .git/hooks
tee .git/hooks/pre-commit << EOF
#!/bin/bash

# Record the current state of the working directory
git diff > /tmp/git-diff-before.txt

# IDE Helper
php artisan ide-helper:generate > /dev/null
php artisan ide-helper:eloquent > /dev/null
php artisan ide-helper:models -N > /dev/null

# Laravel Pint
./vendor/bin/pint --repair > /dev/null

# Record the state after running the commands
git diff > /tmp/git-diff-after.txt

if ! cmp -s /tmp/git-diff-before.txt /tmp/git-diff-after.txt; then
    echo "Some files were changed. Please add the changes to the commit." >&2
    exit 1
fi
EOF

chmod +x .git/hooks/pre-commit
