# Script to convert computer html to human html/readable output.
s/&lt;/</g
s/&gt;/>/g
s/&apos;/'/g
s/&amp;amp;/\&/g
s/&#146;/'/g
s/&amp;mdash;/-/g
s/<a[^>]*>//g
s/<\/a>//g
s/<p>*<\/p>//g
s/^[ \t]*//;s/[ \t]*//
s/<title>//
s/<\/title>//
s/<description>/   /
s/<\/description>//
