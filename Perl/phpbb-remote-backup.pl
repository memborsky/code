#!/usr/bin/perl
#
# pbbackup.pl
#
# © Copyright 2005 By John Bokma, http://johnbokma.com/
#
# Last updated: 2005-12-15 18:24:40 -0600
#
# 2005-12-07 - Now supports re-authentication

use strict;
use warnings;

use Getopt::Long;
use Pod::Usage;
use LWP::UserAgent;
use HTML::TreeBuilder;


sub show_help {

    print <<HELP;
Usage: pbbackup [options] [url]
Options:
    -l user   login with specified admin name
    -pw passw login with specified admin password
    -f        filename of the back up (ignores -d)
    -d        write backup to the specified directory,
              using yyyymmdd-hhmmss.sql.gz as filename
              format
    -h        this message
HELP

    exit 1;
}


my $username;
my $password;
my $filename;
my $dir = '.';
my $verbose = 0;
my $help = 0;


GetOptions(

    "l=s"  => \$username,
    "pw=s" => \$password,
    "f=s"  => \$filename,
    "d=s"  => \$dir,
    'h'    => \$help,

) or show_help;

$help and show_help;

my $url = shift;

defined $username or show_help;
defined $password or show_help;
defined $url      or show_help;

substr( $url, -1 ) eq '/' or $url .= '/';


my $ua = LWP::UserAgent->new();

# make POST redirectable
push @{ $ua->requests_redirectable }, 'POST';

# login as board administrator
my $login_url = "${url}login.php";
my $posting_url = "${url}posting.php";

my $response = $ua->post(

    $login_url, [

        username => $username,
        password => $password,
        autlogin => 'off',
        redirect => '',
        login    => 'Log in',
    ]
);
$response->is_success or
    die "Login failed: ", $response->status_line, "\n";

# obtain the session id
my $root = HTML::TreeBuilder->
    new_from_content( $response->content );

my $sid_link = $root->look_down( _tag => 'a',
    href => qr/sid=[a-z0-9]+$/ );

defined $sid_link or
    die "No link containing a session id found\n";

my ( $sid ) = $sid_link->attr( 'href' )
    =~ /sid=([a-z0-9]+)$/;

defined $sid or
    die "No session id found\n";

$root->delete;

# Re-authenticate (required for more recent phpBB versions)
$response = $ua->post(

    $posting_url
    . "?mode=newtopic&amp;f=3&amp;sid=$sid", [

        username => $username,
        subject  => 'Test',
        message  => 'Testing',
        disable_bbcode => 'off',
        disable_smilies => 'on',
        notify   => 'off',
        mode     => 'newtopic',
        f        => '3',
        topictype => '0',
        post     => 'submit'
    ]
);
$response->is_success or
    die "Re-authentication failed: ", $response->status_line, "\n";

$root = HTML::TreeBuilder->
    new_from_content( $response->content );

$root->delete;
