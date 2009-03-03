NAME = count
CACHE = ../../cache/jmd_$(NAME).php
RELEASE = ../../releases/jmd_$(NAME).txt
MKPL = ~/bin/mkpl
all:
	php $(MKPL) $(NAME).php $(CACHE) $(RELEASE)

clean:
	rm $(CACHE) $(RELEASE)

