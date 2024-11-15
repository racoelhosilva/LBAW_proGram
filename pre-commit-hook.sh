mkdir -p .git/hooks
tee .git/hooks/pre-commit << EOF
#!/bin/bash

# IDE Helper
php artisan ide-helper:generate > /dev/null
php artisan ide-helper:eloquent > /dev/null
php artisan ide-helper:models -N > /dev/null

# Laravel Pint
./vendor/bin/pint --repair > /dev/null

if ! git diff --quiet; then
    echo "Some files were changed. Please add the changes to the commit." >&2
    exit 1
fi
EOF

chmod +x .git/hooks/pre-commit
