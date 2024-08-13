#!/bin/bash

# Define the target location and the symlink location
TARGET="/etc/creare/auth.json"
SYMLINK="$HOME/.config/composer/auth.json"

# Create the .config/composer directory if it doesn't exist
mkdir -p "$HOME/.config/composer"

# Check if the symlink already exists
if [ -L "$SYMLINK" ]; then
    echo "Symlink already exists."
elif [ -e "$SYMLINK" ]; then
    echo "A file already exists at $SYMLINK. Please remove it first."
else
    # Create the symlink
    ln -s "$TARGET" "$SYMLINK"
    echo "Symlink created successfully."
fi
