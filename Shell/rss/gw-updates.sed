# Script to convert computer html to human html/readable output.
s/&lt;/</g
s/&gt;/>/g
s/&apos;/'/g
s/&quot;/"/g
s/&amp;/\&/g
s/&#146;/'/g
s/<a[^>]*>//g
s/<\/a>//g
s/^[ \t]*//;s/[ \t]*//
s/<title>//
s/<\/title>//
s/<description>/   /
s/<\/description>//
s/<ul[^>]*>/\[list\]/g
s/<\/ul>/\[\/list\]/g
s/<li>/\[\*\]/g
s/<\/li>//g
s/<br>//g
