1  #!/usr/bin/perl -w
2  # rss2html - converts an RSS file to HTML
3  # It take one argument, either a file on the local system,
4  # or an HTTP URL like http://slashdot.org/slashdot.rdf
5  # by Jonathan Eisenzopf. v1.0 19990901
6  # Copyright (c) 1999 Jupitermedia Corp. All Rights Reserved.
7  # See http://www.webreference.com/perl for more information
8  #
9  # This program is free software; you can redistribute it and/or modify
10  # it under the terms of the GNU General Public License as published by
11  # the Free Software Foundation; either version 2 of the License, or
12  # (at your option) any later version.
13
14  # INCLUDES
15  use strict;
16  use XML::RSS;
17  use LWP::Simple;
18
19  # Declare variables
20  my $content;
21  my $file;
22
23  # MAIN
24  # check for command-line argument
25  die "Usage: rss2html.pl (<RSS file> | <URL>)\n" unless @ARGV == 1;
26
27  # get the command-line argument
28  my $arg = shift;
29
30  # create new instance of XML::RSS
31  my $rss = new XML::RSS;
32
33  # argument is a URL
34  if ($arg=~ /http:/i) {
35      $content = get($arg);
36      die "Could not retrieve $arg" unless $content;
37      # parse the RSS content
38      $rss->parse($content);
39
40  # argument is a file
41  } else {
42      $file = $arg;
43      die "File \"$file\" does't exist.\n" unless -e $file;
44      # parse the RSS file
45      $rss->parsefile($file);
46  }
47
48  # print the HTML channel
49  &print_html($rss);
50
51  # SUBROUTINES
52  sub print_html {
53      my $rss = shift;
54      print <<HTML;
55  <table bgcolor="#000000" border="0" width="200"><tr><td>
56  <TABLE CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF" BORDER=0 width="100%">
57    <tr>
58    <td valign="middle" align="center" bgcolor="#EEEEEE"><font color="#000000" face="Arial,Helvetica"><B><a href="$rss->{'channel'}->{'link'}">$rss->{'channel'}->{'title'}</a></B></font></td></tr>
59  <tr><td>
60  HTML
61
62      # print channel image
63      if ($rss->{'image'}->{'link'}) {
64    print <<HTML;
65  <center>
66  <p><a href="$rss->{'image'}->{'link'}"><img src="$rss->{'image'}->{'url'}" alt="$rss->{'image'}->{'title'}" border="0"
67  HTML
68          print " width=\"$rss->{'image'}->{'width'}\""
69        if $rss->{'image'}->{'width'};
70    print " height=\"$rss->{'image'}->{'height'}\""
71        if $rss->{'image'}->{'height'};
72    print "></a></center><p>\n";
73      }
74
75      # print the channel items
76      foreach my $item (@{$rss->{'items'}}) {
77    next unless defined($item->{'title'}) && defined($item->{'link'});
78    print "<li><a href=\"$item->{'link'}\">$item->{'title'}</a><BR>\n";
79      }
80
81      # if there's a textinput element
82      if ($rss->{'textinput'}->{'title'}) {
83    print <<HTML;
84  <form method="get" action="$rss->{'textinput'}->{'link'}">
85  $rss->{'textinput'}->{'description'}<BR>
86  <input type="text" name="$rss->{'textinput'}->{'name'}"><BR>
87  <input type="submit" value="$rss->{'textinput'}->{'title'}">
88  </form>
89  HTML
90      }
91
92      # if there's a copyright element
93      if ($rss->{'channel'}->{'copyright'}) {
94    print <<HTML;
95  <p><sub>$rss->{'channel'}->{'copyright'}</sub></p>
96  HTML
97      }
98
99      print <<HTML;
100  </td>
101  </TR>
102  </TABLE>
103  </td></tr></table>
104  HTML
105  }
