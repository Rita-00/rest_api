#!/bin/bash

commitRegex='^([0-9]+|merge|hotfix)'
if ! grep -qE "$commitRegex" "$1"; then
    echo "Wrong. Must be [number]+message"
    exit 1
fi