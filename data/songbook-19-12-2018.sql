-- Create syntax for TABLE 'album'
CREATE TABLE `album` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `release_date` datetime NOT NULL,
  `artist_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`artist_id`),
  KEY `fk_album_artist1_idx` (`artist_id`),
  CONSTRAINT `fk_album_artist1` FOREIGN KEY (`artist_id`) REFERENCES `artist` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'artist'
CREATE TABLE `artist` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `info` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'genre'
CREATE TABLE `genre` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'playlist'
CREATE TABLE `playlist` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`user_id`),
  KEY `fk_playlist_user1_idx` (`user_id`),
  CONSTRAINT `fk_playlist_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'playlist_song_rel'
CREATE TABLE `playlist_song_rel` (
  `playlist_id` int(11) NOT NULL,
  `song_id` int(11) NOT NULL,
  `song_genre_id` int(11) NOT NULL,
  PRIMARY KEY (`playlist_id`,`song_id`,`song_genre_id`),
  KEY `fk_playlist_song_rel_song1_idx` (`song_id`,`song_genre_id`),
  CONSTRAINT `fk_playlist_song_rel_playlist1` FOREIGN KEY (`playlist_id`) REFERENCES `playlist` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_playlist_song_rel_song1` FOREIGN KEY (`song_id`, `song_genre_id`) REFERENCES `song` (`id`, `genre_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'song'
CREATE TABLE `song` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `content` blob,
  `genre_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`genre_id`),
  KEY `fk_song_genre1_idx` (`genre_id`),
  CONSTRAINT `fk_song_genre1` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'song_album_rel'
CREATE TABLE `song_album_rel` (
  `album_id` int(11) NOT NULL,
  `song_id` int(11) NOT NULL,
  PRIMARY KEY (`album_id`,`song_id`),
  KEY `fk_song_album_rel_album_idx` (`album_id`),
  KEY `fk_song_album_rel_song1_idx` (`song_id`),
  CONSTRAINT `song_album_rel_ibfk_1` FOREIGN KEY (`song_id`) REFERENCES `song` (`id`) ON DELETE CASCADE,
  CONSTRAINT `song_album_rel_ibfk_2` FOREIGN KEY (`album_id`) REFERENCES `album` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'user'
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;