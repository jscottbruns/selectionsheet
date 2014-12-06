<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Qmail-Scanner Frequently Asked Questions</title>
  </head>

  <body bgcolor="#FFFFFF">
<font size="-3"><b>Last Updated:</b> <?php echo   date ("l dS of F Y h:i:s A",filemtime($_SERVER["SCRIPT_FILENAME"])). " GMT"; ?></font>
    <h1>Qmail-Scanner Frequently Asked Questions</h1>



    <p></p>
    <ol>
      <li><a href="#system">System Issues</a></li>
      <li><a href="#resources">Resource Issues</a></li>
      <li><a href="#cs">Content-Scanner Issues</a></li>
      <li><a href="#cfg">Configuration Issues</a></li>
      <li><a href="#soft">Other Software Integration Issues</a></li>
    </ol>

    <p></p>

    <h2><a name="system">System/Software Issues</a></h2>
    <p></p>
    <ol>
      <li><b>It doesn't work!</b>. Please ensure your problem is a Qmail-Scanner problem before posting to the list asking for help. Make sure you test your install of Qmail completely - ensuring local and remote deliveries occur as expected. Then change it to call Qmail-Scanner. This especially applies to RPM Qmail installs</li>
      <li><b>Here's a bug report/problem...</b>If you ever email the mailing-list with an error report, <em>PLEASE</em> ensure you tell us what OS you are running, the Q-S version number and include the part of qmail-queue.log that shows where the error occurs. Without that information, no-one can help.</li>
      <li><b>Can't do suid</b>: some perl distributions
have decided that as running suid perl scripts is a rare event, they won't
install/enable it by default. On these systems this package won't work. Typically
the fix is:<BR>
<pre> 
chown root /usr/bin/suidperl
chmod 4711 /usr/bin/suidperl
</pre>
<BR>
...if suidperl exists, otherwise you will have to find that component package 
of perl to install (e.g under Redhat it's an RPM call perl-suidperl)</li>
      <li><b>YOU HAVEN'T DISABLED SET-ID SCRIPTS IN THE KERNEL YET</b>: some perl distributions have decided that running suid perl scripts is BAD, and they specifically don't support it. For these systems, you have no option but to either:
	<ol>
	  <li>install setuid perl components - e.g. for Redhat there is a separate <b>perl-suidperl</b> RPM you have to install</li>
	  <li>install perl from source - compiling in setuid support, or</li>
	  <li>install a compiled setuid "wrapper" - which then calls qmail-scanner-queue.pl. In the contrib directory there is an example C program, taken straight out of the perlsec manual. Check the Makefile and "make ; make install" as root. You <em>must</em> then remove the setuid setting on qmail-scanner-queue.pl:<pre>chmod 0755 /var/qmail/bin/qmail-scanner-queue.pl</pre> as the binary does that bit instead, and stop running perl as suidperl (i.e. "<code>#!/usr/bin/perl</code>" instead of "<code>#!/usr/bin/suidperl</code>").</li>
	</ol>
</li>
      <li><b>Running ./configure shows errors</b>. Seen a bit under older Solaris systems. If you have bash installed on that system, run configure using that instead (i.e. "<b>bash ./configure ....</b>")</li>
      <li><b>How to install Perl modules</b>: I find the CPAN auto-install module to be the easiest way of doing it. e.g. to install Time::HiRes, try<pre> perl -e 'use CPAN; install Time::HiRes'</pre> - now that's sweet :-). You can always use the <a href="http://search.cpan.org/search?module=Time::HiRes">CPAN search engine</a> instead to find it</li>
      <li><b>Can I use metamail instead of reformime?</b>: No. metamail used to be supported, but has been found to totally barf on email containing<i> multipart/alternative</i> MIME attachments - which all HTML email viruses contain. As such it is <em>not</em> supported.</li>
      <li><b>It says I must have reformime from the maildrop package! But I like procmail/[insert favorite MDA here]. Why do I have to...</b>. You don't. reformime is needed for the extracting of MIME attachments by Qmail-Scanner - it doesn't have to be used by any other part of your system. Keep using procmail/whatever :-)</li>
      <li><b>It says I must have maildrop installed. Will I have to install .qmail files for all users...?</b>. You don't. Maildrop isn't used at all - only the reformime program that comes with it is. Qmail-Scanner installs at a very low level and doesn't require any per-user configuration.</li>
      <li><b>How do I "patch" Qmail with the QMAILQUEUE patch?</b>: First off, if you're asking that question, then you are in for a learning experience as none of this stuff is intuitive. If you are a Linux user, have you thought about using Bruce Guenter's <a href="http://untroubled.org/qmail+patches/">patched qmail-1.03 RPM</a> - just "rpm --rebuild" as root to build your own i386.rpm. If you're not in that camp, but still willing, then do the following:
	<ul>
	  <li>tar zxvf qmail-1.03.tar.gz</li>
	  <li>patch -p0  &lt; qmailqueue-patch</li>
	</ul>
	That will patch two files under the <b>qmail-1.03/</b> directory. Then you would build as stated in the Qmail documentation.</li>
      <li><b>Tried that - and the patch failed!. </b>Try GNU patch - systems such as Solaris have problems with their standard patch program</li>
      <li><b>My Redhat 9 systems compiles qmail fine - but crashes all the time</b>: Redhat9 changes a fundamental component that triggers a bug in Qmail (nothing to do with Qmail-Scanner). See the <a href="http://www.qmail.org/top.html#patches">Qmail Website</a> for details and links to the required patch</li>
      <li><b>Sometimes I see Q-S fail with "Malformed UTF-8 character..."</b>: bug in perl-5.8.0. Most RH systems these days default to setting their LANG to "en_US.UTF-8" instead of "C". This causes a whole bunch of Unicode support to be turned on within Perl - which has some bug in it that some char sequences within e-mail can trigger. The fix is to force qmail-smtpd to run with "LANG=C export LANG" before it can call Q-S. Simply edit <b>/service/smtpd/run</b> and add that LANG line somewhere at the top.</li>
      <li><b>Qmail-Scanner needs to run under it's own usercode - how do I set that up?</b>. Well, that depends on your OS. Most Unix systems have a "useradd" command for adding users. Simply ensure the account "qscand" is created, with group "qscand", and that the home directory be anywhere <em>except</em> <b>/var/spool/qmailscan</b> (make it <b>/home/qscand</b> instead). As the account will never be used by anything other than Qmail-Scanner, set the shell to <b>/bin/false</b> or the like - i.e. make it useless as a normal account. Linux systems and the like can do all that in one command: <BR>
	<pre>useradd -c "Qmail-Scanner Account" -s /bin/false -m qscand</pre>
</li>
      <li><b>My Linux system reports "modprobe: Can't locate module
      block-major-xx" whenever a mail message comes in</b>. Some virus scanners do full Boot Sector
      scans every time they operate - as well as scanning the actual
      files. This triggers Linux to try to dynamically install a
      kernel module to deal with it - which doesn't exist. To remove
      these annoying messages, simply add the following lines to
	/etc/conf.modules (or /etc/modules.conf) and "<b>depmod -a</b>":
	<pre>
	  alias block-major-8 off
	  alias block-major-22 off
	  alias block-major-33 off
	  alias block-major-34 off
	</pre></li>
      <li><b>How do I know I've installed it correctly?</b>.
      Run <b>test_installation.sh</b>
	from the <i>contrib/</i> directory to test how Qmail-Scanner handles
      virus-infected emails. Just run it and it'll explain how it sends some
test messages through your system, some with the EICAR test virus and one without.</li>
      <li><b>qmail-inject:unable to exec qq</b>. Something's gone wrong. Look in both <b>/var/spool/qmailscan/qmail-queue.log</b> and your syslog messages for the cause.</li>
      <li><b>How do I add QMAILQUEUE support to supervise-style startup scripts?</b>. Don't. There are now too many different ways of doing things under daemontools/supervise. I cannot be bothered documenting them all. Instead set it under the tcpserver smtp rules file (you're using Qmail - so you already know what that is - right? :-). That way you can even setup Qmail-Scanner to only scan mail from particular SMTP client IP address ranges/etc. This is now the <em>only</em> officially supported mechanism. Set it something like this:<p>
	  <pre>
#/etc/tcpserver/smtp.rules
#
# No Qmail-Scanner at all for mail from 127.0.0.1
127.:allow,RELAYCLIENT="",RBLSMTPD="",QMAILQUEUE="/var/qmail/bin/qmail-queue"
# Use Qmail-Scanner without SpamAssassin on any mail from the local network
# [it triggers SpamAssassin via the presence of the RELAYCLIENT var]
10.:allow,RELAYCLIENT="",RBLSMTPD="",QMAILQUEUE="/var/qmail/bin/qmail-scanner-queue.pl"
#
# Use Qmail-Scanner with SpamAssassin on any mail from the rest of the world
:allow,QMAILQUEUE="/var/qmail/bin/qmail-scanner-queue.pl"
</pre>

</p>
	Then run "<i>maketcprules</i>" or something like "<i>tcprules /etc/tcp.smtp.cdb /etc/tcp.smtp.tmp < /etc/tcp.smtp</i>" to rebuild the database.
</li>
    </ol>

    <p></p>
    <h2><a name="resources">Resource Issues</a></h2>
    <p></p>

    <ol>
      <li>Ensure your Qmail startup script (e.g.
<b>/etc/rc.d/init.d/qmail</b>) has high enough memory limits to allow the extra
	load of perl and the virus scanners it calls! Increase via softlimit the amount of memory a process can use to 6-10M to cover it (e.g. <b>softlimit -a 9000000 tcpserver.....</b>). Note that this is <em>very</em> dependent on OS and virus scanner characteristics - you will have to experiment. If you find issues with Qmail-Scanner, try upping softlimit to something silly like 50M, then reduce it down until it fails again to find the "sweet spot". Then add another Meg to be safe :-)</li>
      <li><b>Syslog reports <pre>perl: error in loading shared
libraries:</pre>or<PRE>failed to map segment from shared object: Cannot
allocate memory</PRE></b>This is due to Qmail's startup script (e.g.
<b>/etc/rc.d/init.d/qmail</b>) having it's memory limits set too low. There isn't
enough memory available to the process to deal with invoking perl/etc</li>
      <li><b>How do I figure out how much memory to set via softlimit?</b><br>
	Simple. Just emulate the runtime environment of Qmail-Scanner and see how the scanners you call react:<p>
</p>
	<pre>cd /tmp
softlimit -a YOUR_MEMORY_LIMIT &lt;scanner&gt; &lt;scanner options&gt; .
</pre>
	If that fails, then run it without the "softlimit" option. If that works, then your scanner needs more memory. Just keep upping YOUR_MEMORY_LIMIT by 1000000 (i.e. 1Mb) until it starts working again. I'd also suggest throwing a few large zip files and PowerPoint/Word docs in the /tmp dir to fully reflect a large complex incoming mail message. Obviously your scanner <em>*always*</em> needs to work 100% - so don't be too stingy on the YOUR_MEMORY_LIMIT amount - but at the same time - don't make it arbitrarily high - as you are opening yourself up to DoS attacks.
</li>
      <li><b>Syslog reports: Maximum time exceeded. Something cannot handle this message.</b> This is due to some part of the scanning process not being able to process the received message in the 20 minutes (default) allocated to the task. Typically it's due to a bad pattern file in a virus scanner "hanging" on certain message types. There is nothing that can be done except wait until that fault in the virus scanner you're using is fixed.</li>
      <li><b>SMTP clients hang when sending messages to Qmail-Scanner.</b> This is because Qmail-Scanner processes the message completely before returning "OK" to the client. This doesn't matter when the client is a remote mail server (there's no-one there to get impatient), but when the remote client is a user - it can be annoying. The fix (as such) is to install Qmail-Scanner on a non-standard port (e.g. 26), and put a "pure" Qmail server up on port 25, which forwards all mail to port 26. That way the user sees immediate acceptance of their message, and the system will process it at it's own rate. Note: there is a downside. Now you have the situation where all mail is hitting Qmail-Scanner from <em>one</em> address. As Qmail-Scanner uses the client IP address to decide whether to run things like SpamAssassin, how will it cope? Answer: it won't. If you really want to do this, the only solution is to use TWO addresses. One propagated via DNS MX records for the Internet to use (running Qmail-Scanner directly), and the other for only your users to use (gatewaying to Qmail-Scanner on port 26). If you are an ISP - or care about security - the tcpserver rules file can be used to limit access to the second address - to ensure only your dialup/whatever users can use it.</li>
    </ol>
    <p></p>

<h2><a name="cs">Content-Scanner Issues</a></h2>
    <p></p>

    <ol>
      <li><b>./configure doesn't find supported scanner 'X'</b>. ./configure checks your PATH plus a few other known directories for the virus scanners. If you have installed it somewhere else, simply alter your PATH so that it can find it, then run <b>./configure</b> again.</li>
      <li><b>AVP is slow</b> Apparently the -V option really slows it down. Removing that from the <i>sub avp_scanner {...}</i> may improve performance at a loss of scanning strength.</li>
      <li><b>AVPdaemon doesn't work!</b>. Well sorry, but that's hardly Qmail-Scanners' fault now is it...</li>
      <li><b>My AV works differently than the scripts
	  assume</b>. Fact: Anyone running anti-virus protection <em>must</em> be running up-to-date
	versions of the AV systems we own in order to get adequate
	protection. Qmail-Scanner will <em><u>not</u></em> support
	older versions of virus scanners - it's just not worth the
	hassle. Conversely, you might find that you've just upgraded to the latests release of your AV product, and now Qmail-Scanner doesn't work, as the new version doesn't support the options Q-S is using. Obviously - I'd like to get a patch ASAP! ;-)</li>
      <li><b>Scanner XXX doesn't work</b>. See above :-) Anyway, it
	must go without saying that there is no way Qmail-Scanner is
	going to work on your system until you have a working AV
	system in place. It cannot "magically" fix things for
	you. Make sure you test the AV scanner as a non-priv user. There
	appears to be a few people who mis-install AV systems so that
	only root can run them, then wonder why Q-S doesn't work. Don't forget, Q-S runs as the qscand account for <em>all</em> mail.</li>
      <li><b>Scanner XXX isn't supported</b>. If you can read perl, see how
the other scanners are supported (e.g. sub-uvscan.pl) and write one to match your scanner!
Send me the results and it'll be added! (I cannot justify buying every
scanner in existence just to support it - so I must rely on you for that!)</li>
      <li><b>What do I need to know to run daemonized versions of antivirus scanners?</b>: For those AV's that support a daemonized version, the trick is to ensure it runs as an account that can read the files under /var/spool/qmailscan - i.e. it needs to run as "qscand". Also ensure the daemon never crashes (maybe run it under daemontools), and that it is configured to limit the amount of resource it will swallow to scan a particular message. e.g. limit the number of times it will unpack archives. Oh yes - then ensure you use whatever mechanism the package states to keep the pattern files up-to-date, and ensure the daemon is notified when such updates occur so that it can read the new patterns. You obviously need to refer to the particular AV's documentation to figure out all of these things </li>
      <li><b>How on earth do I get Trophie/Sophie to work with Qmail-Scanner?</b>. Good question. It's a bit of work, but it's very worthwhile as trophie/sophie  outperform their static counterparts by an order of magnitude. 
<P>
Compile, install and run (e.g. via daemontools in /services/sophie/run) as:<P>

	<code> exec /usr/local/bin/softlimit -a 20000000 /usr/local/bin/setuidgid qscand /usr/local/sbin/sophie  >> /var/log/sophie.log 2>&1</code>
<P>
This will ensure trophie/sophie runs as the same usercode that Qmail-Scanner
	does, and that the socket file that Qmail-Scanner must talk to
	is under the same tree as the rest of Qmail-Scanner. The softlimit command (could use ulimit there of course) ensures these daemons don't run away, swallowing up all RAM or the likes. Don't
	forget, trophie/sophie still relies entirely on your Trend/Sophos
	install. Ensure your Trend/Sophos pattern files are up-to-date as
	normal, and  restart trophie/sophie every time you update the
	files to ensure it's up-to-date too. If you don't, trophie/sophie will <em>CRASH-AND-BURN</em> and Qmail-Scanner will start reporting temporary errors until you fix it!. Note that this example has a softlimit of 20M. That is way higher than is needed, but it is safer to do with daemonized scanners like Trophie/Sophie than stand-alone scanners as they limit the max number of concurrent sessions themselves. Setting a higher limit like that reduces the likelihood than an automated pattern file update at 2am suddenly make your Qmail-Scanner server start reporting out-of-memory errors! :-)</li>
      <li><b>Gah! Trophie/Sophie died and I'm getting <i>Couldn't connect</i>
	  syslog errors!!!</b>. Trophie/Sophie is still  a development
	product. There are bugs. Please join the trophie/sophie list and ask
	there. But I would recommend running it as
	a daemontools service so that if the daemon died, it would be auto-restarted... </li>
      <li><b>Trophie/Sophie can't run - permission denied errors occur</b>. Sounds like you are either not running *ophie as <b>qscand</b>, or if you are, then <b>qscand</b> can't write to the default positions of /var/run/*ophie or /var/run/*ophie.pid (which makes sense - that directory is only writable by root!). The latter can only be fixed by recompiling *ophie so that these files are somewhere where <b>qscand</b> has write access - like /var/spool/qmailscan (or perhaps /home/qscand?). Look at the options to </b>./configure</b> for those packages, you should be able to figure it out :-)</li>
      <li><b>Hey! Sophie/Trophie not handling large attachments</b>. By default, trophie/sophie is compiled with a 30 second max. scan time for a file. If the message contains a large attachment (or you have a slow system), then it may never be able to scan it in that 30 seconds. As such, you must edit sophie.c/trophie.c and change TROPHIE_TIMEOUT/SOPHIE_TIMEOUT from 30 to some larger number - several minutes is probably best (e.g 180).</li>
      <li><b>I've got the daemonized clamd installed - but Q-S reports "access denied" when calling it (i.e. clamdscan cannot open files under /var/spool/qmailscan/)</b>. Simple: you've got clamdscan running as a different usercode to Q-S - it needs to be qscand so that it can read the files!!! :-)</li>
      <li><b>clamdscan isn't detecting XXX</b>. Well, you have to ask yourself - how is it set on your system? Qmail-Scanner <em>is not</em> your antivirus scanner. Please read the documentation for ClamAV and ensure your clamav.conf is configured like you want it to be. This comment can apply to any AV...</li>
      <li><b>How do I configure/install SpamAssassin?</b>. Does this look like the <a href="http://spamassassin.org/">SpamAssassin web site</a>? Seriously, you must be comfortable with running any of these content-scanning systems before you look at running Qmail-Scanner - it cannot magically make things work for you. Remember - SpamAssassin is only detected correctly if it is running in daemon-mode (spamd). Besides that, it's up to you how you want to run it. I'd recommend not running it in the default mode, where it alters all messages that it thinks are spam - that will annoy too many users. <b>Don't forget:</b> <em>Qmail-Scanner is there messing around with all mail into AND OUT OF your site!!! Don't embarrass yourself!!!</em>  I'd recommend the following settings for <b>/etc/mail/spamassassin/local.cf</b>. 
	<p></p>

	<pre>
skip_rbl_checks 1
required_hits 5
rewrite_subject 0
use_terse_report 1
dns_available yes
	</pre><p>...and then run it as "<b>/usr/bin/spamd -L -x -u spamc</b>"</p>
	<p>Why? Those settings stops SpamAssassin from altering the message in a way the user will see; they will have to know about the added headers in order to act (better: it should be the users choice - IMHO). Secondly, disable RBL checks as you should have done them earlier (i.e via rblsmtpd or the likes). You will gain a <em>large</em> speedup to SA if it doesn't have to do RBL lookups. Thirdly, the "-x" option stops spamd attempting to create user preferences files in each users directory (that includes qscand home dir...). Unless you know what you're doing, you should stop that.</p>
</li>
      <li><b>Messages marked as spam are not quarantined!</b>. Feature - not a bug. Spam detection is not 100% accurate - there is a fair number of false-positives. As Qmail-Scanner affects all mail entering your system, it would be prudent to make decisions regarding "what is spam" a user-decision instead of a system-wide one. Simply tell your users that they should filter their mail based on the following string:<p></p>
	<code>             X-Spam-Status: Yes,</code><p>or see below for <b>Subject:</b> tagging.</p></li>
      <li><b>What's this fast_spamassassin vs verbose_spamassassin?</b>. Quick intro to SpamAssassin: The spamc client makes a <em>NETWORK</em> connection to the spamd daemon. It has to send the message over that network link to the daemon, and wait for it's reply. In <em>fast</em> mode, "spamc -c" is used: this means the daemon just tells the client the status of the message - it doesn't transmit the message with all those added SpamAssassin headers back to the client. In <em>verbose</em> mode, the spamc client receives the entire message back again - with the added SpamAssassin headers. Sending and receiving a mail message from the daemon is twice as slow as just sending it - hence "verbose" vs "fast". I recommend "fast" - it will scale better.</li>
      <li><b>I want "fast_spamassassin" for performance  - but I want the Subject: header tagged as "SPAM" too!</b> Boy - you don't want much do you! :-) Anyway - you can. Simply change the "--scanner" option to "fast_spamassassin=STRING" and "STRING" ("SPAM:" is a good value) will be prepended to the Subject line of every message marked as Spam. If you want all that cool extra detail from SA (e.g. the reasons for a particular score), then there is no option but to use "verbose_spamassassin"</li>
      <li><b><a name="qs_sa">Why are some mails not scanned by SpamAssassin?</a></b>. Qmail-Scanner will only pass the message to SpamAssassin if it originates from an external SMTP client. This is defined by whether or not the standard Qmail RELAYCLIENT environment variable is set. i.e. if the mail originates locally, it isn't scanned by SA. This is done for performance reasons and to cut down on false positives (i.e. your local users will never complain that their email is being classified as spam :-) If you explicitly want to scan some/all local SMTP clients email too, then set <b>QS_SPAMASSASSIN="on"</b> within the tcpserver rules file.</li>
      <li><b><a name="qs_ta">Why do some messages get tagged with "SA:0(?/?)" instead of numbers?</a></b>. SpamAssassins "spamd" daemon has a max e-mail size limit. If a message is larger than that size, it just returns with no score (as it skipped it). As such Qmail-Scanner has no numbers to report, so it uses "?" to show that happened. Also, if some error occurs within SpamAssassin, Qmail-Scanner returns "?" again - showing that SA couldn't do the job on that particular mail message</li>
      <li><b>What about per-user SpamAssassin configs?</b>. Q-S calls spamc as "<code>spamc -c -u &lt;rcpt to&gt;</code>" i.e. "username" is the recipient email address. Note that this only happens when there is one recipient. If you are running spamd with a SQL backend, then this should allow you to do per-user SA settings. </li>
      <li><b>Hey! what's this "Disallowed MIME characters found in headers" doing to &lt;product X&gt; messages!!!</b>. That internal function in Qmail-Scanner blocks any MIME e-mail message that contains either NULL characters ("\0") or CR chars ("\r") in the headers. Such characters are used by viruses/trojans to bypass antivirus scanners, and the presence of this feature automatically blocks them. It also has the side effect of blocking quite a bit of SPAM - as most spammers hand-code their SPAM generators and don't know how to read RFCs. Unfortunately, some "good" e-mail generators  are also broken/misconfigured and trigger the quarantine action. Typically what has happened is that a config file for the generator has been edited from within Windows [Unix uses LF "\n" to delimit end of lines, whereas Windows uses "CRLF "\r\n"], and of course, it's thrown CR chars into what should have been text-only config details. Only the "owner" of the particular e-mail generator can fix this problem. Qmail-Scanner is doing it's job.</li>
    </ol>
    <p></p>
    <h2><a name="cfg">Configuration Issues</a></h2>
    <p></p>
    <ol>
      <li><b>Hey! How do I stop ./configure discovering XXX - I don't want to use that!</b>. Currently your can't. I'd recommend renaming it before running ./configure until Qmail-Scanner v2 series comes out. That issue will be fixed then.</li>
      <li><b>What's this "--local-domains" option do?</b>. This is only ever referred to if you have configured Q-S to send notifications to recipients (by default it only notifies senders and admin). If a virus is email'ed to a mailing-list, and notifications to recipients is enabled, then you end up spamming the list with these notices - something I am horrified at. To this end I've introduced the "--local-domains" concept where now only recipients containing the domains mentioned will receive notifications. This will go a long way to limiting damage. In general, try to avoid sending notifications to recipients. Place the onus on fixing the problem where it belongs - the sender.</li>
      <li><b>How do I make Qmail-Scanner only scan mail for some local domains?</b>. 2 words: "MX records" :-) Put two IP addresses on your mail server, change the MX records for those domains you want scanned go to one IP address, and the rest to the other. Then you simply have an instance of qmail-smtpd running on each address - one with QMAILQUEUE defined and one without. This is <em>*majorly*</em> better than coding Qmail-Scanner to ignore certain addresses - this way Qmail-Scanner isn't called at all for the domains you don't want to protect. Similarly, I'd recommend that ISPs tell their customers to point their SMTP clients at a Qmail server NOT running Qmail-Scanner, but configure that server to send all its mail to the Qmail-Scanner server that handles your incoming Internet mail. That way they are all scanned for viruses, only incoming Internet mail is scanned for Spam (exception: <a alt="Why are some mails not scanned by SpamAssassin?" href="#qs_sa">see above</a>), and your users don't see any delays in their SMTP submissions due to Qmail-Scanner scanning their mail in real-time.</li>
      <li><b>How do I make Qmail-Scanner only scan mail for some local users?</b>. You don't. Qmail-Scanner is a server-based product. If you want per-user control, then you'll really have to look for something else.</li>
      <li><b>I'm upgrading and I can't remember my previous config details!</b>. Simple, check out the initial comments in the old script. It shows how <b>configure</b> was called :-)</li>
      <li><b>How do I make Qmail-Scanner send English text to English users, and XXX to XXX users?</b> You can't. email (unlike HTTP) has no official way of stating what language/locale the sender prefers - so no system can intelligently decide what language to use for return messages. In case you haven't noticed - any bounces your email servers generate are only ever in one language, so why do you think Qmail-Scanner should be different :-)</li>
    </ol>

    <h2><a name="soft">Software Integration Issues</a></h2>

    <ul>
     <li><b>Q-S doesn't work with Vpopmail</b> Vpopmail - when used in its "pop-before-smtp" configuration - basically strips out environment variables set within the tcpserver SMTP rules file - specifically the 
QMAILQUEUE environment variable. As it is responsible for starting qmail-smtpd, 
that means Qmail-Scanner never gets called. This is really a bug with Vpopmail, 
but a workaround is to set QMAILQUEUE within /service/smtpd/run instead. However
, you must realise that you will <em>lose</em> Q-S functionality - such as altering Q-S components based on SMTP server IP address, etc. This will only get worse... If you don't like it - join the Vpopmail list and bring it up there - this is not anything Qmail-Scanner can do anything about. <b>Note:</b> Alex Pleiner has created a patch for vpopmail's roaming users feature
	that allows it to interoperate with Qmail-Scanner. See the contrib/
	directory for the patch (vpopmail-issues.eml)</li>

      <li><b>I get &lt;vendor product&gt; reporting an error when Q-S runs</b> Well that could be anything... One thing that has been showing up as an issue recently is that the permissions on pattern file downloads are incorrect. As most SysAdmins run the AV program as root, it all works fine, and then they wonder why it doesn't work via Q-S....</li>
    </ul>




    <hr>
    <address><a href="mailto:jhaar@users.sourceforge.net">Jason Haar</a></address>
<!-- Created: Tue Jan 15 15:41:52 NZDT 2002 -->
<!-- hhmts start -->
Last modified: Mon May 10 12:29:51 NZST 2004
<!-- hhmts end -->
<p><font size="-3"><b>Last Updated:</b> <?php echo   date ("l dS of F Y h:i:s A",filemtime($_SERVER[SCRIPT_FILENAME])). " GMT";?></font>
<P>
  </body>
</html>
