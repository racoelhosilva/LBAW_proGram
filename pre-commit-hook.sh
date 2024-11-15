mkdir -p .git/hooks
tee .git/hooks/pre-commit << EOF
#!/bin/bash

function ide_helper() {
    php artisan ide-helper:generate 
    php artisan ide-helper:eloquent
    php artisan ide-helper:models -N
}

function laravel_pint() {
    ./vendor/bin/pint --repair
}

if ! ide_helper; then
    echo "IDE Helper files regenerated or an error occurred" >&2
    exit 1
elif ! laravel_pint; then
    echo "Laravel Pint reformatted files" >&2
    exit 1
fi
EOF

chmod +x .git/hooks/pre-commit
