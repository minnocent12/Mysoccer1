-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 26, 2024 at 05:56 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mysoccer`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `password_reset_code` varchar(6) DEFAULT NULL,
  `password_reset_expires` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`, `firstname`, `lastname`, `email`, `profile_picture`, `password_reset_code`, `password_reset_expires`) VALUES
(5, 'admin', '$2y$10$0mlnrtyUh0DvtnpKrxt5HuhdrTVw.SKABDaMsYF7GfF0dVU0U4oZ6', '2024-10-15 04:25:39', 'star', 'star', 'me@gmail.com', 'IMG_9760.jpeg', NULL, NULL),
(6, 'pi', '$2y$10$Z0fzlsewy1.0f2YYqMsRMONRvGU5Z8DlgeUWc75jVKMfOLSFIeXxm', '2024-10-15 21:23:55', 'pi', 'pi', 'pi@gmail.com', NULL, NULL, NULL),
(7, 'me', '$2y$10$lxWMqg6ifZyqgJ1ebnAMeOuox1ZOHO6Y1zPMUlGl37qvmhApuFpkS', '2024-11-10 23:48:43', 'me', 'me', 'me@gmail.com', NULL, '227441', 1732597023),
(8, 'mirenge', '$2y$10$N4JcOmmPd3GPBzlylJw9SO.ZnNNMF5hS3gmxj2K25huIyNfA49Q7K', '2024-11-19 04:26:04', 'Mirenge', 'Innocent', 'mirenge.innocent@gmail.com', NULL, '797362', 1732569074);

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `team1_id` int(11) NOT NULL,
  `team2_id` int(11) NOT NULL,
  `match_date` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `match_time` time NOT NULL,
  `team1_score` int(11) DEFAULT NULL,
  `team2_score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `tournament_id`, `team1_id`, `team2_id`, `match_date`, `location`, `match_time`, `team1_score`, `team2_score`) VALUES
(17, 17, 33, 34, '2024-11-29 00:00:00', 'atlanta', '17:10:00', NULL, NULL),
(18, 17, 33, 35, '2024-11-23 00:00:00', 'atlanta', '16:11:00', 4, 3),
(19, 17, 33, 36, '2024-11-28 00:00:00', 'atlanta', '18:28:00', NULL, NULL),
(20, 17, 33, 38, '2024-12-03 00:00:00', 'atlanta', '20:26:00', NULL, NULL),
(21, 17, 34, 35, '2024-12-25 00:00:00', 'Atlanta', '16:28:00', 1, 1),
(22, 17, 34, 36, '2024-12-24 00:00:00', 'atlanta', '16:29:00', NULL, NULL),
(23, 17, 34, 37, '2024-11-29 00:00:00', 'atlanta', '22:45:00', 0, 0),
(24, 17, 34, 38, '2024-11-30 00:00:00', 'atlanta', '16:31:00', NULL, NULL),
(25, 19, 39, 40, '2024-12-25 00:00:00', 'Duluth', '12:40:00', 1, 3),
(26, 19, 39, 40, '2025-01-29 00:00:00', 'Duluth', '12:20:00', NULL, NULL),
(27, 19, 39, 42, '2024-12-26 00:00:00', 'Duluth', '20:14:00', 4, 0),
(28, 19, 40, 41, '2024-12-17 00:00:00', 'Duluth', '23:30:00', NULL, NULL),
(29, 19, 40, 42, '2024-12-27 00:00:00', 'duluth', '12:53:00', NULL, NULL),
(30, 20, 43, 44, '2025-01-16 00:00:00', 'Atlanta', '23:11:00', 2, 1),
(31, 20, 43, 45, '2025-01-08 00:00:00', 'Atlanta', '15:33:00', NULL, NULL),
(32, 20, 43, 46, '2025-01-13 00:00:00', 'Atlanta', '22:22:00', NULL, NULL),
(33, 20, 43, 47, '2025-01-29 00:00:00', 'Atlanta', '11:11:00', 2, 0),
(34, 20, 43, 48, '2025-01-10 00:00:00', 'Atlanta', '22:02:00', NULL, NULL),
(35, 20, 44, 45, '2025-01-22 00:00:00', 'Atlanta', '12:12:00', NULL, NULL),
(36, 20, 44, 46, '2025-01-17 00:00:00', 'Atlanta', '03:32:00', 6, 0),
(37, 20, 44, 47, '2025-01-23 00:00:00', 'Atlanta', '12:32:00', NULL, NULL),
(38, 20, 44, 48, '2025-01-21 00:00:00', 'Atlanta', '21:02:00', 2, 1),
(39, 20, 45, 46, '2025-01-21 00:00:00', 'Atlanta', '18:07:00', NULL, NULL),
(40, 20, 45, 47, '2025-01-18 00:00:00', 'Atlanta', '22:02:00', 1, 1),
(41, 20, 45, 48, '2025-01-25 00:00:00', 'Atlanta', '10:22:00', 0, 3),
(42, 20, 46, 47, '2025-01-27 00:00:00', 'Atlanta', '05:06:00', 0, 1),
(43, 20, 46, 48, '2025-01-11 00:00:00', 'Atlanta', '05:04:00', NULL, NULL),
(44, 20, 47, 48, '2025-01-20 00:00:00', 'Atlanta', '12:03:00', 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `date_submitted` timestamp NOT NULL DEFAULT current_timestamp(),
  `replied_to` int(11) DEFAULT NULL,
  `is_admin_reply` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date_posted` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `summary`, `content`, `image`, `date_posted`, `created_at`, `admin_id`) VALUES
(24, 'Local Team Wins Championship', 'The local soccer team clinched the championship title in a thrilling match last weekend.', 'In a closely contested final, the local team showcased their skills and determination, leading to a well-deserved victory against their fiercest rivals. The match was held in front of a packed stadium, with fans cheering on their team from the first whistle to the last. The atmosphere was electric, with both sets of supporters creating an incredible backdrop for what would become a historic moment in local soccer.\r\nThe game started with an early goal from the local team, igniting celebrations among the fans. As the match progressed, tensions ran high, with both teams exchanging chances. The opposition equalized in the second half, putting the local team under pressure. However, the resilience and tactical discipline displayed by the players were commendable. The coaching staff’s strategy was evident as the team regained control and launched a series of counterattacks.\r\nWith just minutes left on the clock, the local team scored a stunning winner, sealing their championship victory. Fans erupted in joy as players celebrated on the field, lifting the trophy in front of their loyal supporters. This win not only marks a significant achievement for the club but also strengthens their position in the community, inspiring young athletes to pursue their soccer dreams.\r\n', 'local1.jpg', '2024-11-01', '2024-11-01 17:06:50', 5),
(25, 'Rising Star Shines in Youth League', 'A young player from the youth academy scored a hat-trick in the latest match.', 'The spotlight was firmly on 16-year-old forward Nduwayesu Mussa during last weekend’s youth league match, where he delivered an outstanding performance by scoring a hat-trick. His first goal came within the first 10 minutes, showcasing his lightning speed and exceptional finishing ability. The crowd erupted in applause, recognizing the young talent who has been a standout player throughout the season.\r\nMussa’s second goal demonstrated his impressive skills as he dribbled past two defenders before slotting the ball into the bottom corner of the net. The coaching staff has been closely monitoring his progress, and this match solidified his status as one of the league’s most promising talents. With each touch of the ball, Mussa showed maturity beyond his years, making intelligent runs and providing support to his teammates. His hat-trick not only led his team to victory but also caught the attention of scouts from various clubs.\r\nAs the final whistle blew, the team celebrated their win, with Mussa being named Player of the Match. The young forward expressed his gratitude for the support from his coaches and teammates. His success story is a testament to the hard work and dedication he has put into his training. With his talent and determination, there is no doubt that forward Nduwayesu Mussa is a player to watch in the future, and he represents hope for the local soccer community.\r\n', 'PHOTO-2024-08-31-18-33-14.jpg', '2024-11-01', '2024-11-01 17:15:16', 5),
(26, 'Community Fundraiser for Youth Soccer', 'A local fundraiser aims to support youth soccer programs in the area.', 'The community is coming together to support local youth soccer programs through an exciting fundraiser scheduled for next month. The event will feature a charity match, raffle prizes, food stalls, and various activities designed to engage families and soccer enthusiasts. With the aim of raising funds for equipment, uniforms, and facility improvements, this initiative reflects the community\'s commitment to nurturing young talent and promoting the sport.Local players, coaches, and families are rallying to support the event, demonstrating their passion for soccer and its positive impact on youth development. The charity match will see former players take to the field, offering fans a chance to relive past glories while raising money for a worthy cause. Additionally, raffle prizes donated by local businesses will be up for grabs, further encouraging community participation and support for youth soccer initiatives.\r\nOrganizers are optimistic about the event\'s success, emphasizing that the funds raised will directly benefit young athletes in the community. The initiative aims to provide more children with access to soccer programs, promoting physical fitness, teamwork, and discipline. By uniting the community through this fundraiser, organizers hope to foster a love for the game and inspire the next generation of soccer stars. The event promises to be a fun-filled day for all ages, reinforcing the importance of soccer as a vital part of the community’s culture.\r\n', 'PHOTO-2023-09-02-20-58-09.jpg', '2024-11-01', '2024-11-01 17:17:49', 5),
(27, 'Soccer Training Tips for Young Players', 'Essential training tips to help young soccer players improve their skills.', 'For young soccer players eager to enhance their skills and performance, understanding the fundamentals of training is crucial. Proper training not only focuses on physical conditioning but also emphasizes technique, teamwork, and mental resilience. Coaches often encourage young athletes to practice specific drills that target various skills, such as dribbling, passing, and shooting, ensuring a well-rounded development.\r\nOne essential tip for young players is to maintain a regular practice schedule. Consistency is key in mastering any skill, and setting aside time for dedicated practice can lead to significant improvements. Incorporating a mix of individual drills and team practices helps players understand their roles within a group while honing their personal skills. Additionally, focusing on strength and conditioning exercises can enhance physical fitness, enabling players to perform at their best during matches.\r\nFinally, aspiring soccer players should also prioritize a positive mindset. Developing mental resilience is just as important as physical training, as the sport can often be challenging and competitive. Young players are encouraged to set personal goals, celebrate their progress, and learn from setbacks. By fostering a love for the game and maintaining a balanced approach to training, young athletes can lay a strong foundation for their soccer journey and enjoy the many benefits that come from playing the sport.\r\n', 'PHOTO-2024-03-09-19-49-58 5.jpg', '2024-11-01', '2024-11-01 17:21:28', 5),
(28, 'Player of the Month: Midfielder Shines', 'The club has named its Player of the Month, recognizing the midfielder\'s outstanding performances.', 'The club proudly announced its Player of the Month, awarding the honor to midfielder Patrick Gatungane for her exceptional performances throughout the month. Patrick\'s contributions have been vital to the team’s success, showcasing her versatility and technical skills on the field. Her ability to control the tempo of the game and distribute the ball effectively has earned her praise from teammates, coaches, and fans alike.\r\nDuring this period, Patrick displayed remarkable resilience and determination in crucial matches, often stepping up when the team needed her the most. Her goal-scoring ability, combined with her playmaking skills, has made her a standout performer in the midfield. Patrick\'s work ethic during training and her positive attitude have also inspired her teammates, creating a sense of camaraderie within the squad. Her commitment to continuous improvement has not gone unnoticed, solidifying her reputation as one of the league\'s top midfielders.\r\nIn an interview following the announcement, Patrick expressed her gratitude for the recognition, attributing her success to the support of her teammates and coaching staff. She emphasized the importance of teamwork in achieving individual accolades and expressed her determination to keep pushing herself to new heights. As the season progresses, fans and analysts will be watching closely to see how Patrick continues to impact the team\'s performance, and her Player of the Month award is a testament to her hard work and talent.\r\n', 'PHOTO-2023-09-03-01-40-23 2.jpg', '2024-11-01', '2024-11-01 17:27:59', 5),
(29, 'Behind the Scenes: A Day in the Life of a Player', 'An inside look at the daily routine of a professional soccer player.', 'What does a typical day look like for a professional soccer player? To provide fans with a glimpse behind the curtain, we followed midfielder Tom Harris as he navigated a day in his life. Harris’s day begins early in the morning with a nutritious breakfast designed to fuel his body for the demands of training. Afterward, he heads to the club’s training facility, where he joins his teammates for a rigorous workout designed to enhance their fitness and skills.\r\nTraining sessions are meticulously planned, focusing on various aspects of the game, including passing drills, tactical exercises, and set-piece practices. Coaches emphasize teamwork and communication, ensuring that every player is on the same page. After a few hours of intense training, Harris participates in video analysis sessions, where the coaching staff reviews footage from recent matches. This feedback loop helps players understand their performance and implement improvements in real-time.\r\nBeyond the pitch, players like Harris maintain a structured routine that includes recovery sessions, physical therapy, and nutrition management. He emphasizes the importance of mental health, often taking time for mindfulness practices to stay focused and relaxed. In the evening, Harris dedicates time to community engagements and sponsor events, reinforcing the connection between the team and its supporters. Through this insightful glimpse into a day in the life of a player, fans can appreciate the dedication and effort that goes into achieving success at the highest level.\r\n', 'PHOTO-2024-07-26-21-11-41.jpg', '2024-11-01', '2024-11-01 17:50:04', 5),
(30, 'Youth Soccer Programs: A Path to Success', 'Exploring the importance of youth soccer programs in developing young talent.', 'Youth soccer programs play a crucial role in nurturing the next generation of talent, providing young players with opportunities to develop their skills, make friends, and learn valuable life lessons. These programs often serve as the foundation for a successful soccer career, introducing children to the sport at an early age and fostering a love for the game. As they progress through the ranks, players gain access to quality coaching, competitive play, and resources that enable them to hone their abilities.\r\nThe benefits of youth soccer extend far beyond the field. Participation in team sports promotes physical fitness, instills discipline, and teaches essential skills such as teamwork and communication. Coaches often prioritize not only athletic development but also character building, helping players navigate challenges both in sports and in life. Through a supportive environment, young athletes learn the importance of hard work, resilience, and respect for others, values that will serve them well in any future endeavor.\r\nLocal soccer clubs are increasingly investing in their youth programs, recognizing the potential for long-term success. By providing scholarships and resources, they aim to create pathways for talented players to succeed, regardless of their background. As these young athletes pursue their dreams, the community rallies around them, celebrating their achievements and fostering a sense of pride. With the continued support of clubs, coaches, and families, youth soccer programs will remain vital in shaping the future of the sport.\r\n', 'PHOTO-2023-09-02-18-55-39.jpg', '2024-11-01', '2024-11-01 17:53:48', 5),
(31, 'The Impact of social media on Soccer', 'An exploration of how social media has transformed the way fans engage with soccer.', 'The rise of social media has revolutionized the way fans engage with soccer, allowing for instantaneous communication and interaction between clubs, players, and supporters. Platforms like Twitter, Instagram, and Facebook have become essential tools for clubs to connect with their fanbase, sharing updates, behind-the-scenes content, and highlights that keep supporters engaged and informed. This direct line of communication has transformed the relationship between clubs and fans, fostering a sense of community and belonging.\r\nFor players, social media serves as a platform to build their personal brands and engage with fans on a more personal level. Athletes can share insights into their lives, promote charitable causes, and connect with supporters in ways that were previously unimaginable. However, this increased visibility also comes with challenges, as players must navigate the complexities of public scrutiny and manage their online personas carefully. The ability to instantly share moments from the pitch can lead to both praise and criticism, highlighting the delicate balance between authenticity and public perception.\r\nMoreover, social media has also changed the landscape of sports journalism, with fans now having access to a wealth of information at their fingertips. Instant updates on match results, player transfers, and injuries are readily available, enabling supporters to stay informed and engaged. However, the spread of misinformation can also pose challenges, as fans must discern credible sources from speculation. Overall, social media\'s impact on soccer is profound, enhancing the fan experience while reshaping the dynamics of the sport in the modern era.\r\n', 'socialmedia.jpg', '2024-11-01', '2024-11-01 18:33:00', 5),
(32, 'The Importance of Community Engagement in Sports', 'Discussing how sports organizations can foster community ties and support local initiatives.', 'Community engagement is a fundamental aspect of sports organizations, playing a crucial role in building strong ties with local populations and promoting positive social change. By actively participating in community initiatives, sports clubs can foster goodwill and strengthen relationships with their supporters. Whether through charity events, youth programs, or volunteer opportunities, these organizations have the power to make a lasting impact on the lives of individuals and families within their communities.\r\nLocal sports teams often serve as a source of pride and identity for their communities, uniting fans from diverse backgrounds around a common passion. By engaging with local residents, sports organizations can create meaningful connections that extend beyond the pitch. Initiatives such as school partnerships, health and wellness programs, and environmental sustainability efforts can enrich the lives of community members and promote a sense of belonging. This reciprocal relationship benefits both the organization and the community, reinforcing the importance of social responsibility.\r\nIn recent years, many sports organizations have stepped up their commitment to community engagement, recognizing the positive impact they can have. By aligning their values with local needs, they can create initiatives that resonate with supporters and amplify their message. As community engagement continues to evolve, the role of sports organizations in fostering social change will only grow, highlighting the profound influence that sports can have on society as a whole. By investing in their communities, sports organizations not only enhance their brand but also play a vital role in shaping a brighter future for all.\r\n', 'PHOTO-2024-08-03-19-58-53 7.jpg', '2024-11-01', '2024-11-01 18:35:15', 5),
(33, 'Tactical Analysis: Key Strategies for Success', 'An in-depth look at the tactical strategies employed by the club to achieve success on the field.', 'This article delves into the tactical strategies that have propelled the club to success in recent seasons. Understanding the importance of a well-structured game plan, the coaching staff has implemented a dynamic approach that emphasizes adaptability and teamwork. By analyzing recent matches, we can identify key elements of the club\'s strategy that have contributed to their achievements.\r\nOne of the standout strategies is the use of high pressing, which aims to regain possession quickly and disrupt the opponent\'s build-up play. This aggressive approach not only puts pressure on the opposing team but also creates scoring opportunities through forced errors. The players\' fitness and tactical awareness are crucial in executing this strategy effectively, allowing them to maintain intensity throughout the match. Additionally, the team utilizes a fluid formation that encourages players to interchange positions, providing unpredictability and depth in attack.\r\nAnother critical aspect of the club\'s success is its focus on set pieces. The coaching staff meticulously prepares players for free kicks and corner situations, analyzing opponent tendencies and developing specific plays. This preparation has led to a significant increase in goals scored from set pieces, providing a valuable edge in tight matches. Overall, the combination of high pressing, positional fluidity, and set-piece proficiency showcases the tactical acumen that defines the club\'s playing style and has positioned them as contenders in their league.\r\n', 'PHOTO-2024-07-26-22-38-00 2.jpg', '2024-11-01', '2024-11-01 18:36:38', 5),
(34, 'The Impact of Technology on Soccer', 'Examining how technology is changing the way soccer is played and viewed.', 'Technology is transforming every aspect of soccer, from training methods to fan engagement. This article examines the impact of technology on the sport, highlighting innovations that are changing the way soccer is played and viewed. One of the most significant advancements has been the introduction of video assistant referees (VAR), which aims to improve decision-making during matches. By providing referees with access to video replays, VAR has sparked discussions about the balance between technology and the human element of officiating.\r\nTraining techniques have also evolved with the integration of technology. Clubs now utilize performance analysis software to track player metrics, allowing coaches to make data-driven decisions that enhance training sessions. Wearable technology, such as GPS trackers and heart rate monitors, provides insights into player fitness levels and workloads, enabling tailored training programs that prevent injuries and optimize performance.\r\nAdditionally, technology has revolutionized fan engagement, providing new ways for supporters to connect with their teams. Social media platforms allow fans to interact with clubs, share experiences, and access exclusive content. Streaming services have made it easier for fans to watch matches from anywhere in the world, further expanding the reach of soccer. As technology continues to evolve, its impact on the game will only grow, shaping the future of soccer for players, coaches, and fans alike.\r\n', 'goalline.jpg', '2024-11-01', '2024-11-01 18:38:28', 5),
(35, 'Soccer Legends: The Players Who Shaped the Game', 'A tribute to the legendary players who have left an indelible mark on soccer history.', 'Soccer legends are the icons of the sport, players who have transcended the game and left a lasting legacy. This article pays tribute to the legendary athletes who have shaped soccer history, celebrating their contributions and the impact they have had on the game. From Pelé to Diego Maradona, these players have inspired generations with their extraordinary talent, skill, and dedication.\r\nPelé, often regarded as one of the greatest players of all time, captured the world\'s attention with his incredible goal-scoring ability and charismatic personality. His influence extended beyond the pitch, as he became a global ambassador for the sport, promoting soccer and inspiring countless young athletes. Similarly, Diego Maradona\'s brilliance and creativity on the field left an indelible mark, culminating in his iconic performance in the 1986 World Cup, where he led Argentina to glory.\r\nBeyond their on-field achievements, these legends have demonstrated the power of soccer to unite people and communities. Their stories continue to inspire new generations, reminding us of the beauty of the game and the remarkable athletes who have shaped its history. As we celebrate the legacy of these soccer icons, we honor their contributions to the sport and the lasting impact they have made on fans worldwide.\r\n', 'PHOTO-2024-07-30-21-29-22.jpg', '2024-11-01', '2024-11-01 18:40:33', 5);

-- --------------------------------------------------------

--
-- Table structure for table `standings_history`
--

CREATE TABLE `standings_history` (
  `id` int(11) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `team_name` varchar(255) NOT NULL,
  `played` int(11) NOT NULL,
  `won` int(11) NOT NULL,
  `drawn` int(11) NOT NULL,
  `lost` int(11) NOT NULL,
  `gf` int(11) NOT NULL,
  `ga` int(11) NOT NULL,
  `gd` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `next_opponent` varchar(255) DEFAULT 'Match not updated',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `standings_history`
--

INSERT INTO `standings_history` (`id`, `tournament_id`, `position`, `team_name`, `played`, `won`, `drawn`, `lost`, `gf`, `ga`, `gd`, `points`, `next_opponent`, `updated_at`) VALUES
(2906, 17, 1, 'Solar Strikers', 1, 1, 0, 0, 4, 3, 1, 3, 'Meteor Mashers', '2024-11-19 19:00:29'),
(2907, 17, 2, 'Cosmic Chargers', 2, 0, 2, 0, 1, 1, 0, 2, 'Meteor Mashers', '2024-11-19 19:01:42'),
(2910, 17, 4, 'Nebula Knights', 2, 0, 1, 1, 4, 5, -1, 1, 'Match not updated', '2024-11-19 19:02:50'),
(2912, 17, 5, 'Comet Blasters', 0, 0, 0, 0, 0, 0, 0, 0, 'Cosmic Chargers', '2024-11-19 19:03:54'),
(2916, 17, 3, 'Asteroid Avengers', 1, 0, 1, 0, 0, 0, 0, 1, 'Match not updated', '2024-11-19 19:05:16'),
(2924, 17, 6, 'Meteor Mashers', 0, 0, 0, 0, 0, 0, 0, 0, 'Cosmic Chargers', '2024-11-19 19:07:21'),
(2987, 19, 2, 'Starfire Squad', 2, 1, 0, 1, 5, 3, 2, 3, 'Eclipse Elite', '2024-11-19 19:35:58'),
(2988, 19, 1, 'Eclipse Elite', 1, 1, 0, 0, 3, 1, 2, 3, ' Rocket Raptors', '2024-11-19 19:36:57'),
(2991, 19, 3, 'Galactic Giants', 0, 0, 0, 0, 0, 0, 0, 0, 'Eclipse Elite', '2024-11-19 19:38:04'),
(2993, 19, 4, ' Rocket Raptors', 1, 0, 0, 1, 0, 4, -4, 0, 'Eclipse Elite', '2024-11-19 19:39:45'),
(3029, 20, 3, 'Africa', 2, 2, 0, 0, 4, 1, 3, 6, 'Europe', '2024-11-19 19:49:09'),
(3035, 20, 1, 'South America', 3, 2, 0, 1, 9, 3, 6, 6, 'Australia', '2024-11-19 19:50:39'),
(3041, 20, 5, 'North America', 2, 0, 1, 1, 1, 4, -3, 1, 'Asia', '2024-11-19 19:51:41'),
(3048, 20, 6, 'Asia', 2, 0, 0, 2, 0, 7, -7, 0, 'Europe', '2024-11-19 19:53:48'),
(3057, 20, 4, 'Australia', 4, 1, 1, 2, 4, 8, -4, 4, 'South America', '2024-11-19 19:55:15'),
(3067, 20, 2, 'Europe', 3, 2, 0, 1, 9, 4, 5, 6, 'Asia', '2024-11-19 19:56:46');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `coach_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `tournament_id`, `name`, `logo`, `coach_name`) VALUES
(33, 17, 'Solar Strikers', 'Solar.jpeg', 'John Smith'),
(34, 17, 'Cosmic Chargers', 'Cosmic.jpeg', 'Sarah Jones'),
(35, 17, 'Nebula Knights', 'nebuka.jpeg', 'Michael Johnson'),
(36, 17, 'Comet Blasters', 'comet.jpeg', 'David Lee'),
(37, 17, 'Asteroid Avengers', 'Asteroid.jpeg', 'Emily Walker'),
(38, 17, 'Meteor Mashers', 'Meteor.jpeg', 'Kevin Brown'),
(39, 19, 'Starfire Squad', 'Starfire.jpeg', 'Olivia White'),
(40, 19, 'Eclipse Elite', 'Eclipse.jpeg', 'Daniel Harris'),
(41, 19, 'Galactic Giants', 'Galactic.jpeg', 'Natalie Green'),
(42, 19, ' Rocket Raptors', ' Rocket.jpeg', 'Chris Taylor'),
(43, 20, 'Africa', 'africa.jpeg', 'Muhammad Ali'),
(44, 20, 'South America', 'SA.jpeg', 'Lucy White'),
(45, 20, 'North America', 'NA.jpeg', 'John Williams'),
(46, 20, 'Asia', 'AFC.jpeg', 'Wang Lee'),
(47, 20, 'Australia', 'australia.jpeg', 'Sophia Mitchell'),
(48, 20, 'Europe', 'uefa.jpeg', 'Richard Clark');

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

CREATE TABLE `tournaments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `num_teams` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `organizer` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `register` int(11) DEFAULT 0,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournaments`
--

INSERT INTO `tournaments` (`id`, `name`, `logo`, `num_teams`, `start_date`, `end_date`, `organizer`, `location`, `register`, `admin_id`) VALUES
(17, 'Galaxy Cup', 'galaxy_cup.jpeg', 10, '2024-11-23', '2025-02-20', 'Galaxy Cup', 'Atlanta', 6, 8),
(18, 'Championship League', 'emerald_league.jpeg', 15, '2024-11-28', '2025-02-21', 'Championship League', 'Atlanta', 0, 8),
(19, 'Phoenix_Championship', 'phoenix_champ.jpeg', 20, '2024-12-04', '2025-02-25', 'Phoenix_Championship', 'Duluth', 4, 5),
(20, 'Continental League', 'continental.jpeg', 6, '2025-01-08', '2025-01-29', 'Continental', 'Marietta', 6, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `team1_id` (`team1_id`),
  ADD KEY `team2_id` (`team2_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin_id` (`admin_id`);

--
-- Indexes for table `standings_history`
--
ALTER TABLE `standings_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_standing` (`tournament_id`,`team_name`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_id` (`tournament_id`);

--
-- Indexes for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `standings_history`
--
ALTER TABLE `standings_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3374;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`team1_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_ibfk_3` FOREIGN KEY (`team2_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `standings_history`
--
ALTER TABLE `standings_history`
  ADD CONSTRAINT `standings_history_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`);

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD CONSTRAINT `tournaments_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
