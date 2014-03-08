# ex: ts=4 sw=4 et filetype=sh

_phd()
{
    local cur opts
    COMPREPLY=()
    cur="${COMP_WORDS[COMP_CWORD]}"
    opts="
        --css
        --color
        --docbook
        --ext
        --forceindex
        --format
        --help
        --highlighter
        --lang
        --list
        --noindex
        --notoc
        --output
        --package
        --partial
        --quit
        --saveconfig
        --skip
        --verbose
        --version
        --xinclude"

    if [[ ${cur} == -* ]] ; then
        COMPREPLY=( $(compgen -W "${opts}" -- ${cur}) )
        return 0
    fi
}

complete -F _phd phd
