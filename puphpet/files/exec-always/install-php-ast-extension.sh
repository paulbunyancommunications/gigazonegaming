#!/usr/bin/env bash
if ! grep -q extension=ast.so "$File"; then
    # Install php-ast extension
    sudo git clone https://github.com/nikic/php-ast.git
    cd php-ast
    sudo phpize
    sudo ./configure
    sudo make install
    echo "extension=ast.so" | sudo tee --append /etc/php.ini
fi;
