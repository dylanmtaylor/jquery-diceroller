#!/bin/bash
#requires imagemagick, GIMP, pngcrush, and advancecomp
mogrify -background none -format png -render *.svg
#http://www.gimp.org/tutorials/Basic_Batch/
#http://developer.gimp.org/api/2.0/libgimp/libgimp-gimpconvert.html#gimp-image-convert-indexed
#the batch-index.scm script needs to be saved in the ~/.gimp-2.6/scripts/ directory
gimp -i -b '(batch-index "*.png" 3)' -b '(gimp-quit 0)'
#finally, run very intense compression algorithms...
find . -nowarn -name '*.png' -type f -exec pngcrush -brute -reduce -fix -l 9 -c 3 {} {}.crush \; -exec mv -fv {}.crush {} \;
rm *.crush
optipng -o7 *png
advpng -z -4 *png
advdef -z -4 *png
