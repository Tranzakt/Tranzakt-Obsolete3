# Tranzakt

Tranzakt is (for the moment at least) a Proof of Concept for a new web database application tool.

Inspired by Fabrik, a Joomla extension, Tranzakt is intended to provide:

* A web-based development environment (Tranzakt Developer) for
browser-based GUI non-code creation of SQL-based data applications
* A run-time environment (Tranzakt Runtime) that is intended to be run without Tranzakt Developer either:
  * stand-alone; or
  * acting as a remote server for main web sites based on CMSes like Joomla running in a separate webserver instance.

Currently the intention is that Tranzakt Developer will maintain metadata tables containing the application design,
and use these to generate PHP files creating a native Laravel application which,
with support from Trazakt Runtime, can execute like any other Laravel PHP application.
It is planned that there will be a close to 1-to-1 mapping between metadata items and lines of code,
with Tranzakt Runtime providing all the necessary sub-classes and traits to extend Laravel standard functionality
and allow this generated code to be highly simplified.

Because this is intended to be a type of Laravel Application Generator,
the intent is **not** to duplicate the Fabrik design
(which constrained by the Joomla framework and by a 1990's style development GUI),
but instead to make as much of Laravel's abilities available through a no-code GUI development as is possible.

It is intended that the Developer environment will be subdivided into several sub-modules
(e.g. Tables, Forms, Visualisers (lists, graphs etc. provided as extensible sub-modules) etc.),
to separate these into manageable functions,
and that each table column / form field will be associated with an Element sub-module.
All of the above should (in general) be free of php and / or JS coding,
defineable only using the Tranzakt Developer GUI.
Depending on how Tranzakt evolves, I can also foresee the likely need for middleware sub-modules
that allow the user to write short PHP or JS code snippets that extend
functionality beyond what can be defined through the GUI.

It is intended that all these sub-modules will use common abstract-interfaces,
and provide:

* Server-side run-time PHP code for display and form processing
* Client-side run-time JS code for display and form processing
* JSON-based parameters that will drive common functionality in the Developer GUI.

It is anticipated that the Runtime environment can be used in several ways:

* User connecting directly to Laravel as a web server, with Tranzakt providing Blade / Vue / React / Livewire etc.
interfaces to include Tranzakt HTML inside a more general web page; or
* User connecting to an alternative webserver e.g. Joomla / Wordpress or other CMS, which
(through Tranzakt extensions in that environment)
then makes a remote call to the Tranzakt API to deliver HTML
which the primary webserver then includes in a web page.
* A RESTful API that can be called generically (and which will serve as a PoC).

Note 1: Because of the need to support the differing user security models of the calling server,
there may need to be separate APIs for e.g. Joomla and Wordpress.
This is something to be determined at a later date.

Note 2: It is anticipated that for a complex form, many DB queries may be required,
and ideally as PHP fibers and Laravel's use of them evolve,
Tranzakt will be structured to submit these queries in parallel.
Similarly, it would be nice if the calling CMS could request the Tranzakt API HTML at the beginning of
it's own processing using an asynchronous request, and then collect the results at the end of its processing using a second call
(possibly delivering these results to the browser using an Ajax call) -
this would allow Tranzakt and the CMS to undertake their own processing in parallel.

It is anticipated that all sub-modules will eventually support all Laravel front-end
technologies and APIs.

This should result in a tool that is both highly performant
(directly executed rather than interpreted from the metadata)
and highly functional (because it builds on the Laravel / Front-End frameworks),
and which has a highly productive modern GUI development environment
that has excellent usability and itself is very responsive.
If my vision comes to pass this will be reminiscent of Fabrik rather than an imitation.

## Target Functionality

* Visualisers (Lists, Graphs etc. of a collection of multiple data records) -
PoC delivering only a List (tabular presentation)
* Forms (a single database record - possibly with sub-rows)
* Runtime - Various Laravel F-E technologies for Runtime (Blade, Tailwind, Livewire, React, Vue etc.) -
PoC delivering only Blade.
* Developer - Ideally a SPA using React or Vue -
PoC should strive to achieve this, but a Blade alternative may be a necessary fallback.
* Themes - a replaceable set of front-end element components to fit with a joomla template design or to give an alternative visual style
* A range of data-field types (limited scope for the proof of concept)
* A debugging log (issued to the JS console) showing key execution details - global summary switch, specific element detail switch.
* Stand-alone operation
* Synchronous and asynchronous API -
ideally a basic generic synchronous API will be delivered as part of the PoC
* Integration with CMS' such as Joomla (inc. integration with the CMS security) - not part of PoC which will be entirely stand-alone
* Full security model including authentication of remote callers and both direct and indirect user authorisation -
the PoC may be delivered without any security.

It is intended that any final product will be delivered with:

* Both run-time and development versions
* Internal documentation (provided using code comments)
* User documentation (provided as help web pages delivered with the development version
* Automated regression tests (to confirm functionality as seen by the user)
* Automated unit tests (to confirm internal functionality esp. boundary cases)
* Internationalisation (UK english will be default language)
* A clever caching system to improve performance - key needs are for caching to
understand and respect security context and for cache entries to be invalidated as needed.
To achieve this the cache naming/hashing needs to be carefully designed to
e.g. invalidate all copies of data that has changed regardless of security context.
* A versioning system (to take a snapshot of a developed system and export it as a self-contained package)
* An SQL upgrade system (to make the schema / data changes to update an older version to a newer version)

However the proof of concept will (as the name suggests) only have the most basic functionality
to provide a base which can then be extended by the community to achieve its potential.

## Design principles

* Design by Contract (another way of ensuring code quality by tightly defining and policing interfaces)
* Carefully architected small objects (because small objects are more maintainable and extensible)
* Focus on core kernel (as a great kernel and excellent abstraction will make delivery of the range of functionality much easier)

## Contributions

Contributions are welcome from anyone wishing to help develop the PoC. Please submit PRs.

A pre-configured development environment for Windows is delivered
in [Tranzakt-dev-laragon-win64](https://github.com/Tranzakt/Tranzakt-dev-laragon-win64).

# Copyright & License

The Tranzakt design and software is Copyright (C) 2022-present Tranzakt Project.

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY, without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
See the GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with this library;
if not, please write to the:
Free Software Foundation Inc.,
51 Franklin Street Fifth Floor,
Boston, MA 02110-1301, USA.
