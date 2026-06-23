const SILUETA = [
	{ id:0, tipo:"M", x:55.19, y:20.73},
	{ id:1, tipo:"C", pc1:{ x:56.07, y:15.83 }, pc2:{ x:56.95, y:10.93 }, fin:{ x:54.62, y:6.98} },
	{ id:2, tipo:"C", pc1:{ x:52.29, y:3.04 }, pc2:{ x:46.76, y:0.04 }, fin:{ x:41.19, y:0.13} },
	{ id:3, tipo:"C", pc1:{ x:35.63, y:0.23 }, pc2:{ x:30.04, y:3.4 }, fin:{ x:27.59, y:7.35} },
	{ id:4, tipo:"C", pc1:{ x:25.14, y:11.29 }, pc2:{ x:25.83, y:16.01 }, fin:{ x:26.18, y:18.37} },
	{ id:5, tipo:"C", pc1:{ x:26.53, y:20.73 }, pc2:{ x:26.53, y:20.73 }, fin:{ x:26.53, y:20.73} },
	{ id:6, tipo:"C", pc1:{ x:25.8, y:21.24 }, pc2:{ x:25.08, y:21.75 }, fin:{ x:25.24, y:23.12} },
	{ id:7, tipo:"C", pc1:{ x:25.41, y:24.48 }, pc2:{ x:26.47, y:26.68 }, fin:{ x:27.27, y:27.7} },
	{ id:8, tipo:"C", pc1:{ x:28.07, y:28.71 }, pc2:{ x:28.61, y:28.53 }, fin:{ x:29.17, y:29.59} },
	{ id:9, tipo:"C", pc1:{ x:29.73, y:30.65 }, pc2:{ x:30.31, y:32.94 }, fin:{ x:31.38, y:34.88} },
	{ id:10, tipo:"C", pc1:{ x:32.45, y:36.82 }, pc2:{ x:34.01, y:38.39 }, fin:{ x:34.48, y:40.36} },
	{ id:11, tipo:"C", pc1:{ x:34.95, y:42.33 }, pc2:{ x:34.32, y:44.68 }, fin:{ x:33.72, y:46.13} },
	{ id:12, tipo:"C", pc1:{ x:33.12, y:47.58 }, pc2:{ x:32.54, y:48.12 }, fin:{ x:28.42, y:49.58} },
	{ id:13, tipo:"C", pc1:{ x:24.3, y:51.04 }, pc2:{ x:16.62, y:53.41 }, fin:{ x:12.61, y:54.98} },
	{ id:14, tipo:"C", pc1:{ x:8.61, y:56.56 }, pc2:{ x:8.27, y:57.33 }, fin:{ x:7.81, y:63.63} },
	{ id:15, tipo:"C", pc1:{ x:7.36, y:69.92 }, pc2:{ x:6.78, y:81.75 }, fin:{ x:6.13, y:90.12} },
	{ id:16, tipo:"C", pc1:{ x:5.48, y:98.5 }, pc2:{ x:4.76, y:103.42 }, fin:{ x:4.23, y:108.99} },
	{ id:17, tipo:"C", pc1:{ x:3.71, y:114.55 }, pc2:{ x:3.37, y:121.02 }, fin:{ x:2.81, y:125.85} },
	{ id:18, tipo:"C", pc1:{ x:2.26, y:130.69 }, pc2:{ x:1.49, y:134.02 }, fin:{ x:0.87, y:136.89} },
	{ id:19, tipo:"C", pc1:{ x:0.25, y:139.75 }, pc2:{ x:-0.21, y:142.15 }, fin:{ x:0.47, y:145.67} },
	{ id:20, tipo:"C", pc1:{ x:1.14, y:149.19 }, pc2:{ x:2.97, y:153.82 }, fin:{ x:4.23, y:156.04} },
	{ id:21, tipo:"C", pc1:{ x:5.49, y:158.25 }, pc2:{ x:6.19, y:158.05 }, fin:{ x:6.82, y:157.17} },
	{ id:22, tipo:"C", pc1:{ x:7.45, y:156.29 }, pc2:{ x:8.01, y:154.72 }, fin:{ x:8.07, y:152.9} },
	{ id:23, tipo:"C", pc1:{ x:8.13, y:151.09 }, pc2:{ x:7.7, y:149.02 }, fin:{ x:7.61, y:146.55} },
	{ id:24, tipo:"C", pc1:{ x:7.52, y:144.09 }, pc2:{ x:7.77, y:141.22 }, fin:{ x:8.26, y:140.65} },
	{ id:25, tipo:"C", pc1:{ x:8.75, y:140.09 }, pc2:{ x:9.47, y:141.82 }, fin:{ x:9.81, y:143.8} },
	{ id:26, tipo:"C", pc1:{ x:10.14, y:145.79 }, pc2:{ x:10.08, y:148.03 }, fin:{ x:10.41, y:149.46} },
	{ id:27, tipo:"C", pc1:{ x:10.73, y:150.89 }, pc2:{ x:11.45, y:151.52 }, fin:{ x:12.05, y:150.04} },
	{ id:28, tipo:"C", pc1:{ x:12.64, y:148.55 }, pc2:{ x:13.12, y:144.95 }, fin:{ x:13.19, y:142.72} },
	{ id:29, tipo:"C", pc1:{ x:13.26, y:140.48 }, pc2:{ x:12.93, y:139.61 }, fin:{ x:12.27, y:137.95} },
	{ id:30, tipo:"C", pc1:{ x:11.61, y:136.28 }, pc2:{ x:10.62, y:133.82 }, fin:{ x:9.99, y:131.75} },
	{ id:31, tipo:"C", pc1:{ x:9.36, y:129.69 }, pc2:{ x:9.09, y:128.02 }, fin:{ x:9.66, y:124.85} },
	{ id:32, tipo:"C", pc1:{ x:10.24, y:121.69 }, pc2:{ x:11.67, y:117.02 }, fin:{ x:12.6, y:113.19} },
	{ id:33, tipo:"C", pc1:{ x:13.53, y:109.35 }, pc2:{ x:13.95, y:106.35 }, fin:{ x:14.21, y:101.55} },
	{ id:34, tipo:"C", pc1:{ x:14.47, y:96.76 }, pc2:{ x:14.57, y:90.16 }, fin:{ x:15.31, y:84.09} },
	{ id:35, tipo:"C", pc1:{ x:16.05, y:78.03 }, pc2:{ x:17.44, y:72.49 }, fin:{ x:18.47, y:72.1} },
	{ id:36, tipo:"C", pc1:{ x:19.51, y:71.71 }, pc2:{ x:20.19, y:76.46 }, fin:{ x:21.11, y:80.61} },
	{ id:37, tipo:"C", pc1:{ x:22.02, y:84.76 }, pc2:{ x:23.16, y:88.31 }, fin:{ x:24.06, y:91.58} },
	{ id:38, tipo:"C", pc1:{ x:24.95, y:94.85 }, pc2:{ x:25.51, y:97.37 }, fin:{ x:25.11, y:101.33} },
	{ id:39, tipo:"C", pc1:{ x:24.71, y:105.28 }, pc2:{ x:23.94, y:107.46 }, fin:{ x:22.84, y:111.8} },
	{ id:40, tipo:"C", pc1:{ x:21.74, y:116.14 }, pc2:{ x:21.46, y:118.34 }, fin:{ x:21.18, y:121.34} },
	{ id:41, tipo:"C", pc1:{ x:20.91, y:124.35 }, pc2:{ x:20.75, y:127.35 }, fin:{ x:20.91, y:131.35} },
	{ id:42, tipo:"C", pc1:{ x:21.07, y:135.35 }, pc2:{ x:21.55, y:140.35 }, fin:{ x:22.09, y:145.19} },
	{ id:43, tipo:"C", pc1:{ x:22.63, y:150.02 }, pc2:{ x:23.24, y:154.69 }, fin:{ x:23.62, y:159.67} },
	{ id:44, tipo:"C", pc1:{ x:23.99, y:164.65 }, pc2:{ x:24.14, y:169.95 }, fin:{ x:23.97, y:174.98} },
	{ id:45, tipo:"C", pc1:{ x:23.8, y:180.01 }, pc2:{ x:23.36, y:184.32 }, fin:{ x:22.86, y:187.84} },
	{ id:46, tipo:"C", pc1:{ x:22.35, y:191.35 }, pc2:{ x:21.81, y:193.85 }, fin:{ x:21.52, y:196.44} },
	{ id:47, tipo:"C", pc1:{ x:21.24, y:199.02 }, pc2:{ x:21.21, y:201.69 }, fin:{ x:21.64, y:205.19} },
	{ id:48, tipo:"C", pc1:{ x:22.08, y:208.69 }, pc2:{ x:22.99, y:213.02 }, fin:{ x:23.84, y:216.69} },
	{ id:49, tipo:"C", pc1:{ x:24.68, y:220.35 }, pc2:{ x:25.47, y:223.35 }, fin:{ x:26.1, y:226.52} },
	{ id:50, tipo:"C", pc1:{ x:26.74, y:229.69 }, pc2:{ x:27.22, y:233.02 }, fin:{ x:27.62, y:236.19} },
	{ id:51, tipo:"C", pc1:{ x:28.01, y:239.35 }, pc2:{ x:28.31, y:242.35 }, fin:{ x:28.42, y:244.27} },
	{ id:52, tipo:"C", pc1:{ x:28.52, y:246.19 }, pc2:{ x:28.43, y:247.01 }, fin:{ x:28.17, y:248.61} },
	{ id:53, tipo:"C", pc1:{ x:27.91, y:250.21 }, pc2:{ x:27.5, y:252.52 }, fin:{ x:27.12, y:254.39} },
	{ id:54, tipo:"C", pc1:{ x:26.74, y:256.25 }, pc2:{ x:26.41, y:257.65 }, fin:{ x:25.95, y:258.89} },
	{ id:55, tipo:"C", pc1:{ x:25.5, y:260.12 }, pc2:{ x:24.93, y:261.19 }, fin:{ x:24.32, y:262.07} },
	{ id:56, tipo:"C", pc1:{ x:23.72, y:262.95 }, pc2:{ x:23.3, y:263.41 }, fin:{ x:23.02, y:264.85} },
	{ id:57, tipo:"C", pc1:{ x:22.73, y:266.29 }, pc2:{ x:22.87, y:267.22 }, fin:{ x:25.78, y:267.89} },
	{ id:58, tipo:"C", pc1:{ x:28.68, y:268.55 }, pc2:{ x:34.29, y:268.45 }, fin:{ x:37.09, y:267.57} },
	{ id:59, tipo:"C", pc1:{ x:39.88, y:266.69 }, pc2:{ x:39.87, y:265.02 }, fin:{ x:39.53, y:263.02} },
	{ id:60, tipo:"C", pc1:{ x:39.2, y:261.02 }, pc2:{ x:38.53, y:258.69 }, fin:{ x:38.02, y:255.69} },
	{ id:61, tipo:"C", pc1:{ x:37.5, y:252.69 }, pc2:{ x:37.14, y:249.02 }, fin:{ x:37.11, y:243.02} },
	{ id:62, tipo:"C", pc1:{ x:37.08, y:237.02 }, pc2:{ x:37.38, y:228.83 }, fin:{ x:37.73, y:221.88} },
	{ id:63, tipo:"C", pc1:{ x:38.09, y:214.93 }, pc2:{ x:38.47, y:209.69 }, fin:{ x:38.5, y:204.19} },
	{ id:64, tipo:"C", pc1:{ x:38.53, y:198.69 }, pc2:{ x:38.2, y:193.02 }, fin:{ x:38.27, y:189.19} },
	{ id:65, tipo:"C", pc1:{ x:38.35, y:185.35 }, pc2:{ x:38.83, y:183.35 }, fin:{ x:39.38, y:176.68} },
	{ id:66, tipo:"C", pc1:{ x:39.92, y:170.01 }, pc2:{ x:40.53, y:158.7 }, fin:{ x:40.91, y:150.31} },
	{ id:67, tipo:"C", pc1:{ x:41.28, y:141.92 }, pc2:{ x:41.43, y:136.49 }, fin:{ x:41.5, y:133.49} },
	{ id:68, tipo:"C", pc1:{ x:41.56, y:130.49 }, pc2:{ x:41.53, y:129.92 }, fin:{ x:41.92, y:129.64} },
	{ id:69, tipo:"C", pc1:{ x:42.31, y:129.35 }, pc2:{ x:43.13, y:129.35 }, fin:{ x:43.54, y:129.64} },
	{ id:70, tipo:"C", pc1:{ x:43.95, y:129.92 }, pc2:{ x:43.95, y:130.49 }, fin:{ x:43.97, y:133.49} },
	{ id:71, tipo:"C", pc1:{ x:43.98, y:136.49 }, pc2:{ x:44.01, y:141.92 }, fin:{ x:44.45, y:150.3} },
	{ id:72, tipo:"C", pc1:{ x:44.88, y:158.69 }, pc2:{ x:45.73, y:170.02 }, fin:{ x:46.36, y:176.69} },
	{ id:73, tipo:"C", pc1:{ x:47, y:183.35 }, pc2:{ x:47.42, y:185.35 }, fin:{ x:47.36, y:189.19} },
	{ id:74, tipo:"C", pc1:{ x:47.3, y:193.02 }, pc2:{ x:46.76, y:198.69 }, fin:{ x:46.67, y:204.19} },
	{ id:75, tipo:"C", pc1:{ x:46.58, y:209.69 }, pc2:{ x:46.94, y:215.02 }, fin:{ x:47.33, y:221.92} },
	{ id:76, tipo:"C", pc1:{ x:47.73, y:228.82 }, pc2:{ x:48.14, y:236.92 }, fin:{ x:48.05, y:242.97} },
	{ id:77, tipo:"C", pc1:{ x:47.97, y:249.02 }, pc2:{ x:47.36, y:252.69 }, fin:{ x:46.85, y:255.69} },
	{ id:78, tipo:"C", pc1:{ x:46.33, y:258.69 }, pc2:{ x:45.91, y:261.02 }, fin:{ x:45.68, y:263.02} },
	{ id:79, tipo:"C", pc1:{ x:45.46, y:265.02 }, pc2:{ x:45.43, y:266.69 }, fin:{ x:48.38, y:267.57} },
	{ id:80, tipo:"C", pc1:{ x:51.33, y:268.45 }, pc2:{ x:57.34, y:268.55 }, fin:{ x:60.2, y:267.89} },
	{ id:81, tipo:"C", pc1:{ x:63.05, y:267.22 }, pc2:{ x:62.81, y:265.79 }, fin:{ x:62.42, y:264.72} },
	{ id:82, tipo:"C", pc1:{ x:62.02, y:263.65 }, pc2:{ x:61.46, y:262.95 }, fin:{ x:60.96, y:262.07} },
	{ id:83, tipo:"C", pc1:{ x:60.45, y:261.19 }, pc2:{ x:60, y:260.12 }, fin:{ x:59.69, y:258.89} },
	{ id:84, tipo:"C", pc1:{ x:59.37, y:257.65 }, pc2:{ x:59.2, y:256.25 }, fin:{ x:58.81, y:254.39} },
	{ id:85, tipo:"C", pc1:{ x:58.42, y:252.52 }, pc2:{ x:57.82, y:250.19 }, fin:{ x:57.42, y:248.6} },
	{ id:86, tipo:"C", pc1:{ x:57.03, y:247.02 }, pc2:{ x:56.84, y:246.19 }, fin:{ x:56.95, y:244.27} },
	{ id:87, tipo:"C", pc1:{ x:57.07, y:242.35 }, pc2:{ x:57.49, y:239.35 }, fin:{ x:58.04, y:236.19} },
	{ id:88, tipo:"C", pc1:{ x:58.58, y:233.02 }, pc2:{ x:59.24, y:229.69 }, fin:{ x:59.83, y:226.52} },
	{ id:89, tipo:"C", pc1:{ x:60.41, y:223.35 }, pc2:{ x:60.92, y:220.35 }, fin:{ x:61.66, y:216.69} },
	{ id:90, tipo:"C", pc1:{ x:62.4, y:213.02 }, pc2:{ x:63.38, y:208.69 }, fin:{ x:63.84, y:205.19} },
	{ id:91, tipo:"C", pc1:{ x:64.29, y:201.69 }, pc2:{ x:64.23, y:199.02 }, fin:{ x:64.01, y:196.44} },
	{ id:92, tipo:"C", pc1:{ x:63.78, y:193.85 }, pc2:{ x:63.39, y:191.35 }, fin:{ x:63.01, y:187.84} },
	{ id:93, tipo:"C", pc1:{ x:62.64, y:184.32 }, pc2:{ x:62.28, y:179.79 }, fin:{ x:62.23, y:174.87} },
	{ id:94, tipo:"C", pc1:{ x:62.17, y:169.95 }, pc2:{ x:62.42, y:164.65 }, fin:{ x:62.78, y:159.67} },
	{ id:95, tipo:"C", pc1:{ x:63.15, y:154.69 }, pc2:{ x:63.63, y:150.02 }, fin:{ x:64.02, y:145.19} },
	{ id:96, tipo:"C", pc1:{ x:64.4, y:140.35 }, pc2:{ x:64.69, y:135.35 }, fin:{ x:64.8, y:131.35} },
	{ id:97, tipo:"C", pc1:{ x:64.9, y:127.35 }, pc2:{ x:64.83, y:124.35 }, fin:{ x:64.59, y:121.34} },
	{ id:98, tipo:"C", pc1:{ x:64.35, y:118.32 }, pc2:{ x:63.93, y:115.3 }, fin:{ x:62.76, y:111.88} },
	{ id:99, tipo:"C", pc1:{ x:61.59, y:108.46 }, pc2:{ x:60.05, y:105.43 }, fin:{ x:59.26, y:101.34} },
	{ id:100, tipo:"C", pc1:{ x:58.47, y:97.24 }, pc2:{ x:58.78, y:95.69 }, fin:{ x:59.69, y:91.5} },
	{ id:101, tipo:"C", pc1:{ x:60.6, y:87.32 }, pc2:{ x:61.18, y:84.76 }, fin:{ x:61.86, y:80.61} },
	{ id:102, tipo:"C", pc1:{ x:62.54, y:76.46 }, pc2:{ x:63.08, y:71.71 }, fin:{ x:64.12, y:71.77} },
	{ id:103, tipo:"C", pc1:{ x:65.15, y:71.83 }, pc2:{ x:66.67, y:76.7 }, fin:{ x:67.67, y:82.77} },
	{ id:104, tipo:"C", pc1:{ x:68.67, y:88.84 }, pc2:{ x:69.14, y:96.09 }, fin:{ x:69.65, y:101.22} },
	{ id:105, tipo:"C", pc1:{ x:70.16, y:106.35 }, pc2:{ x:70.7, y:109.35 }, fin:{ x:71.74, y:113.19} },
	{ id:106, tipo:"C", pc1:{ x:72.78, y:117.02 }, pc2:{ x:74.3, y:121.69 }, fin:{ x:75.1, y:124.85} },
	{ id:107, tipo:"C", pc1:{ x:75.89, y:128.02 }, pc2:{ x:75.95, y:129.69 }, fin:{ x:75.33, y:131.75} },
	{ id:108, tipo:"C", pc1:{ x:74.71, y:133.82 }, pc2:{ x:73.41, y:136.28 }, fin:{ x:72.59, y:137.95} },
	{ id:109, tipo:"C", pc1:{ x:71.78, y:139.61 }, pc2:{ x:71.45, y:140.48 }, fin:{ x:71.28, y:142.72} },
	{ id:110, tipo:"C", pc1:{ x:71.11, y:144.95 }, pc2:{ x:71.11, y:148.55 }, fin:{ x:71.51, y:149.93} },
	{ id:111, tipo:"C", pc1:{ x:71.9, y:151.31 }, pc2:{ x:72.69, y:150.47 }, fin:{ x:73.41, y:148.17} },
	{ id:112, tipo:"C", pc1:{ x:74.14, y:145.87 }, pc2:{ x:74.8, y:142.11 }, fin:{ x:75.26, y:141.67} },
	{ id:113, tipo:"C", pc1:{ x:75.71, y:141.22 }, pc2:{ x:75.95, y:144.09 }, fin:{ x:75.89, y:146.55} },
	{ id:114, tipo:"C", pc1:{ x:75.83, y:149.02 }, pc2:{ x:75.47, y:151.09 }, fin:{ x:75.53, y:152.9} },
	{ id:115, tipo:"C", pc1:{ x:75.59, y:154.72 }, pc2:{ x:76.07, y:156.29 }, fin:{ x:76.6, y:157.17} },
	{ id:116, tipo:"C", pc1:{ x:77.13, y:158.05 }, pc2:{ x:77.7, y:158.25 }, fin:{ x:79.07, y:156.04} },
	{ id:117, tipo:"C", pc1:{ x:80.43, y:153.82 }, pc2:{ x:82.57, y:149.19 }, fin:{ x:83.46, y:145.67} },
	{ id:118, tipo:"C", pc1:{ x:84.36, y:142.15 }, pc2:{ x:83.99, y:139.75 }, fin:{ x:83.59, y:136.89} },
	{ id:119, tipo:"C", pc1:{ x:83.18, y:134.02 }, pc2:{ x:82.72, y:130.69 }, fin:{ x:82.2, y:125.85} },
	{ id:120, tipo:"C", pc1:{ x:81.68, y:121.02 }, pc2:{ x:81.09, y:114.69 }, fin:{ x:80.24, y:109.06} },
	{ id:121, tipo:"C", pc1:{ x:79.38, y:103.42 }, pc2:{ x:78.26, y:98.5 }, fin:{ x:76.98, y:90.01} },
	{ id:122, tipo:"C", pc1:{ x:75.7, y:81.53 }, pc2:{ x:74.31, y:69.92 }, fin:{ x:73.24, y:63.63} },
	{ id:123, tipo:"C", pc1:{ x:72.16, y:57.33 }, pc2:{ x:71.43, y:56.56 }, fin:{ x:67.64, y:54.98} },
	{ id:124, tipo:"C", pc1:{ x:63.84, y:53.41 }, pc2:{ x:56.98, y:51.04 }, fin:{ x:53.29, y:49.58} },
	{ id:125, tipo:"C", pc1:{ x:49.61, y:48.12 }, pc2:{ x:49.11, y:47.58 }, fin:{ x:48.59, y:46.13} },
	{ id:126, tipo:"C", pc1:{ x:48.07, y:44.68 }, pc2:{ x:47.53, y:42.33 }, fin:{ x:47.99, y:40.37} },
	{ id:127, tipo:"C", pc1:{ x:48.46, y:38.41 }, pc2:{ x:49.92, y:36.85 }, fin:{ x:50.96, y:34.92} },
	{ id:128, tipo:"C", pc1:{ x:51.99, y:32.98 }, pc2:{ x:52.59, y:30.67 }, fin:{ x:53.09, y:29.6} },
	{ id:129, tipo:"C", pc1:{ x:53.59, y:28.53 }, pc2:{ x:53.98, y:28.71 }, fin:{ x:54.68, y:27.7} },
	{ id:130, tipo:"C", pc1:{ x:55.38, y:26.68 }, pc2:{ x:56.38, y:24.48 }, fin:{ x:56.52, y:23.12} },
	{ id:131, tipo:"C", pc1:{ x:56.65, y:21.75 }, pc2:{ x:55.92, y:21.24 }, fin:{ x:55.19, y:20.73} }
]; 

const CABELLO_FONDO = [
	{ id:0, tipo:'M', x:34.66, y:3.52 },
	{ id:1, tipo:'C', pc1:{ x:31.99, y:14.18 }, pc2:{ x:26.75, y:23.85 }, fin:{ x:31.95, y:34.51} },
	{ id:2, tipo:'C', pc1:{ x:27.71, y:33.85 }, pc2:{ x:23.78, y:33.86 }, fin:{ x:21.98, y:38.68} },
	{ id:3, tipo:'C', pc1:{ x:12.92, y:46.52 }, pc2:{ x:6.67, y:46.61 }, fin:{ x:0.2, y:39.84} },
	{ id:4, tipo:'C', pc1:{ x:1.37, y:39.58 }, pc2:{ x:2.33, y:39.55 }, fin:{ x:3.11, y:39.61} },
	{ id:5, tipo:'C', pc1:{ x:5.43, y:39.77 }, pc2:{ x:5.04, y:39.05 }, fin:{ x:6.46, y:37.99} },
	{ id:6, tipo:'C', pc1:{ x:5.56, y:32.86 }, pc2:{ x:2.05, y:36.13 }, fin:{ x:0.2, y:30.81} },
	{ id:7, tipo:'C', pc1:{ x:0.82, y:27.25 }, pc2:{ x:3.22, y:27.3 }, fin:{ x:8.78, y:24.98} },
	{ id:8, tipo:'C', pc1:{ x:16.93, y:22.64 }, pc2:{ x:21.21, y:25.42 }, fin:{ x:21.06, y:10.18} },
	{ id:9, tipo:'C', pc1:{ x:23.68, y:5.16 }, pc2:{ x:30.9, y:3.98 }, fin:{ x:34.66, y:3.52} },
];

const TANGA = [
	{ id:0, tipo:'M', x:22.96, y:111.68 },
	{ id:1, tipo:'C', pc1:{ x:22.89, y:111.72 }, pc2:{ x:22.8, y:111.76 }, fin:{ x:22.76, y:111.83} },
	{ id:2, tipo:'C', pc1:{ x:21.95, y:115 }, pc2:{ x:21.45, y:118.17 }, fin:{ x:21.18, y:121.35} },
	{ id:3, tipo:'C', pc1:{ x:19.72, y:121.3 }, pc2:{ x:39.9, y:129.58 }, fin:{ x:41.92, y:129.64} },
	{ id:4, tipo:'C', pc1:{ x:42.08, y:129.64 }, pc2:{ x:43.54, y:129.64 }, fin:{ x:43.54, y:129.64} },
	{ id:5, tipo:'C', pc1:{ x:45.86, y:129.28 }, pc2:{ x:54.15, y:125.86 }, fin:{ x:64.59, y:121.34} },
	{ id:6, tipo:'C', pc1:{ x:64.92, y:120.34 }, pc2:{ x:63.32, y:111.61 }, fin:{ x:62.76, y:111.88} },
	{ id:7, tipo:'C', pc1:{ x:55.64, y:115.26 }, pc2:{ x:31.55, y:116.48 }, fin:{ x:22.84, y:111.8} },
];

const BRA = [
	{ id:0, tipo:'M', x:24.85, y:50.73 },
	{ id:1, tipo:'L', x:22.47, y:51.56 },
	{ id:2, tipo:'L', x:21.57, y:69.41 },
	{ id:3, tipo:'C', pc1:{ x:18.83, y:74.4 }, pc2:{ x:19.12, y:79.95 }, fin:{ x:20.91, y:83.22} },
	{ id:4, tipo:'C', pc1:{ x:25.66, y:88.46 }, pc2:{ x:30.41, y:86.99 }, fin:{ x:35.16, y:83.56} },
	{ id:5, tipo:'C', pc1:{ x:38.87, y:83.01 }, pc2:{ x:43.5, y:82.9 }, fin:{ x:47, y:83.44} },
	{ id:6, tipo:'C', pc1:{ x:52.11, y:87.27 }, pc2:{ x:58.1, y:87.33 }, fin:{ x:62.56, y:82.65} },
	{ id:7, tipo:'C', pc1:{ x:64.39, y:79.03 }, pc2:{ x:63.58, y:73.68 }, fin:{ x:61.71, y:69.41} },
	{ id:8, tipo:'L', x:58.52, y:51.56 },
	{ id:9, tipo:'L', x:56.55, y:50.73 },
	{ id:10, tipo:'L', x:59.05, y:69.66 },
	{ id:11, tipo:'L', x:47.13, y:70.81 },
	{ id:12, tipo:'H', x:35.99 },
	{ id:13, tipo:'L', x:23.9, y:69.66 },
	{ id:14, tipo:'L', x:24.9, y:50.73 }
];

const CABELLO_FRENTE = [
	{ ind:0, tipo:'M', x:29.21, y:28.31 },
	{ ind:1, tipo:'L', x:26.67, y:20.68 },
	{ ind:2, tipo:'C', pc1:{ x:25.96, y:16.38 }, pc2:{ x:24.51, y:12.24 }, fin:{ x:27.79, y:7.06} },
	{ ind:3, tipo:'C', pc1:{ x:31.8, y:1.37 }, pc2:{ x:36.62, y:0.9 }, fin:{ x:41.38, y:-0.01} },
	{ ind:4, tipo:'C', pc1:{ x:48.58, y:0.72 }, pc2:{ x:52.15, y:3.56 }, fin:{ x:54.59, y:7.06} },
	{ ind:5, tipo:'C', pc1:{ x:57.3, y:11.71 }, pc2:{ x:55.68, y:16.17 }, fin:{ x:55.05, y:20.68} },
	{ ind:6, tipo:'L', x:53.43, y:28.31 },
	{ ind:7, tipo:'L', x:53.53, y:20.37 },
	{ ind:8, tipo:'C', pc1:{ x:54.69, y:9.17 }, pc2:{ x:52.12, y:6.34 }, fin:{ x:41.35, y:3.69} },
	{ ind:9, tipo:'C', pc1:{ x:30.94, y:5.7 }, pc2:{ x:27.75, y:11.02 }, fin:{ x:28.62, y:20.51} },
];

const BARBILLA = [
	{ ind:0, tipo:'M', x:30.77, y:33.84 },
	{ ind:1, tipo:'C', pc1:{ x:34.75, y:42.58 }, pc2:{ x:48.53, y:42.5 }, fin:{ x:51.61, y:33.61} },
];

const OMBLIGO = [
	{ ind:0, tipo:'M', x:41.63, y:107.99 },
	{ ind:1, tipo:'C', pc1:{ x:41.65, y:109.04 }, pc2:{ x:41.79, y:109.93 }, fin:{ x:42.45, y:110.12} },
	{ ind:2, tipo:'C', pc1:{ x:42.77, y:109.56 }, pc2:{ x:42.98, y:108.9 }, fin:{ x:42.86, y:107.91} },
];

const ANTEBRAZO_IZQ = [
	{ ind:0, tipo:'M', x:14.07, y:103.15 },
	{ ind:1, tipo:'M', x:4.03, y:111.44 }, 
	{ ind:2, tipo:'C', pc1:{ x:3.62, y:116.4 }, pc2:{ x:3.29, y:121.7 }, fin:{ x:2.81, y:125.85} },
	{ ind:3, tipo:'C', pc1:{ x:2.26, y:130.69 }, pc2:{ x:1.49, y:134.02 }, fin:{ x:0.87, y:136.89} },
	{ ind:4, tipo:'C', pc1:{ x:0.25, y:139.75 }, pc2:{ x:-0.21, y:142.15 }, fin:{ x:0.47, y:145.67} },
	{ ind:5, tipo:'C', pc1:{ x:1.14, y:149.19 }, pc2:{ x:2.96, y:153.82 }, fin:{ x:4.23, y:156.04} },
	{ ind:6, tipo:'C', pc1:{ x:5.49, y:158.25 }, pc2:{ x:6.19, y:158.05 }, fin:{ x:6.82, y:157.17} },
	{ ind:7, tipo:'C', pc1:{ x:7.45, y:156.29 }, pc2:{ x:8.01, y:154.72 }, fin:{ x:8.07, y:152.9} },
	{ ind:8, tipo:'C', pc1:{ x:8.13, y:151.09 }, pc2:{ x:7.7, y:149.02 }, fin:{ x:7.61, y:146.55} },
	{ ind:9, tipo:'C', pc1:{ x:7.52, y:144.09 }, pc2:{ x:7.77, y:141.22 }, fin:{ x:8.26, y:140.65} },
	{ ind:10, tipo:'C', pc1:{ x:8.75, y:140.09 }, pc2:{ x:9.47, y:141.82 }, fin:{ x:9.81, y:143.8} },
	{ ind:11, tipo:'C', pc1:{ x:10.14, y:145.79 }, pc2:{ x:10.08, y:148.03 }, fin:{ x:10.41, y:149.46} },
	{ ind:12, tipo:'C', pc1:{ x:10.73, y:150.89 }, pc2:{ x:11.45, y:151.52 }, fin:{ x:12.05, y:150.04} },
	{ ind:13, tipo:'C', pc1:{ x:12.64, y:148.55 }, pc2:{ x:13.12, y:144.95 }, fin:{ x:13.19, y:142.72} },
	{ ind:14, tipo:'C', pc1:{ x:13.26, y:140.48 }, pc2:{ x:12.93, y:139.61 }, fin:{ x:12.27, y:137.95} },
	{ ind:15, tipo:'C', pc1:{ x:11.61, y:136.28 }, pc2:{ x:10.62, y:133.82 }, fin:{ x:9.99, y:131.75} },
	{ ind:16, tipo:'C', pc1:{ x:9.36, y:129.69 }, pc2:{ x:9.09, y:128.02 }, fin:{ x:9.66, y:124.85} },
	{ ind:17, tipo:'C', pc1:{ x:10.24, y:121.69 }, pc2:{ x:11.67, y:117.02 }, fin:{ x:12.6, y:113.19} },
	{ ind:18, tipo:'C', pc1:{ x:13.41, y:109.85 }, pc2:{ x:13.8, y:106.96 }, fin:{ x:14.07, y:103.15} },
];

const ANTEBRAZO_DER = [
	{ ind:0, tipo:'M', x:80.5, y:111.13 },
	{ ind:1, tipo:'M', x:69.87, y:102.89 },
	{ ind:2, tipo:'C', pc1:{ x:70.33, y:106.92 }, pc2:{ x:70.84, y:109.85 }, fin:{ x:71.74, y:113.19} },
	{ ind:3, tipo:'C', pc1:{ x:72.78, y:117.02 }, pc2:{ x:74.3, y:121.69 }, fin:{ x:75.1, y:124.85} },
	{ ind:4, tipo:'C', pc1:{ x:75.89, y:128.02 }, pc2:{ x:75.95, y:129.69 }, fin:{ x:75.33, y:131.75} },
	{ ind:5, tipo:'C', pc1:{ x:74.71, y:133.82 }, pc2:{ x:73.41, y:136.28 }, fin:{ x:72.59, y:137.95} },
	{ ind:6, tipo:'C', pc1:{ x:71.78, y:139.61 }, pc2:{ x:71.45, y:140.48 }, fin:{ x:71.28, y:142.72} },
	{ ind:7, tipo:'C', pc1:{ x:71.11, y:144.95 }, pc2:{ x:71.11, y:148.55 }, fin:{ x:71.51, y:149.93} },
	{ ind:8, tipo:'C', pc1:{ x:71.9, y:151.31 }, pc2:{ x:72.69, y:150.47 }, fin:{ x:73.41, y:148.17} },
	{ ind:9, tipo:'C', pc1:{ x:74.14, y:145.87 }, pc2:{ x:74.8, y:142.11 }, fin:{ x:75.26, y:141.67} },
	{ ind:10, tipo:'C', pc1:{ x:75.71, y:141.22 }, pc2:{ x:75.95, y:144.09 }, fin:{ x:75.89, y:146.55} },
	{ ind:11, tipo:'C', pc1:{ x:75.83, y:149.02 }, pc2:{ x:75.47, y:151.09 }, fin:{ x:75.53, y:152.9} },
	{ ind:12, tipo:'C', pc1:{ x:75.59, y:154.72 }, pc2:{ x:76.07, y:156.29 }, fin:{ x:76.6, y:157.17} },
	{ ind:13, tipo:'C', pc1:{ x:77.13, y:158.05 }, pc2:{ x:77.7, y:158.25 }, fin:{ x:79.07, y:156.04} },
	{ ind:14, tipo:'C', pc1:{ x:80.43, y:153.82 }, pc2:{ x:82.57, y:149.19 }, fin:{ x:83.46, y:145.67} },
	{ ind:15, tipo:'C', pc1:{ x:84.36, y:142.15 }, pc2:{ x:83.99, y:139.75 }, fin:{ x:83.59, y:136.89} },
	{ ind:16, tipo:'C', pc1:{ x:83.18, y:134.02 }, pc2:{ x:82.72, y:130.69 }, fin:{ x:82.2, y:125.85} },
	{ ind:17, tipo:'C', pc1:{ x:81.74, y:121.59 }, pc2:{ x:81.2, y:116.21 }, fin:{ x:80.5, y:111.13} },
];

const MAPA_CORPORAL_SILUETA = {
    brazos: [
        { id: 15, lado: -1, fuerza: 0.8 }, { id: 34, lado: 1, fuerza: 0.8 },
        { id: 103, lado: -1, fuerza: 0.8 }, { id: 121, lado: 1, fuerza: 0.8 }
    ],
    busto: [
        { id: 36, lado: -1, fuerza: 0.7 }, { id: 101, lado: 1, fuerza: 0.7 }
    ],
    cintura: [
        { id: 37, lado: -1, fuerza: 0.25 }, { id: 100, lado: 1, fuerza: 0.25 },    // Apoyo
        { id: 38, lado: -1, fuerza: 0.5 },   { id: 99, lado: 1, fuerza: 0.5 }      // Principal
    ],
    abdomen: [
        { id: 39, lado: -1, fuerza: 0.5 }, { id: 98, lado: 1, fuerza: 0.5 }
    ],
    cadera: [
        { id: 40, lado: -1, fuerza: 1 }, { id: 97, lado: 1, fuerza: 1 },
        { id: 41, lado: -1, fuerza: 1 }, { id: 96, lado: 1, fuerza: 1 }
    ],
    muslos: [
        { id: 42, lado: -1, fuerza: 1 }, { id: 95, lado: 1, fuerza: 1 },
        { id: 43, lado: -1, fuerza: 1 }, { id: 94, lado: 1, fuerza: 1 },
        { id: 44, lado: -1, fuerza: 0.5 }, { id: 93, lado: 1, fuerza: 0.5 }	// Apoyo
    ],
    papada: [
        { id: 9, lado: -1, fuerza: 1 }, { id: 127, lado: 1, fuerza: 1 },
        { id: 10, lado: -1, fuerza: 1 }, { id: 126, lado: 1, fuerza: 1 }
    ]
};

const MAPA_CORPORAL_TANGA = {
    abdomen: [
        { id: 1, lado: -1, fuerza: 0.5 }, { id: 6, lado: 1, fuerza: 0.5 }
    ],
    cadera: [
        { id: 2, lado: -1, fuerza: 1 }, { id: 5, lado: 1, fuerza: 1 },
    ]
};

const MAPA_CORPORAL_BRA = {
    busto: [
        { id: 3, lado: -1, fuerza: 0.7 }, { id: 6, lado: 1, fuerza: 0.7 }
    ],
};

const medidasBaseCM = { brazos: 26, busto: 83, cintura: 60, abdomen: 58, cadera: 78, muslos: 41, papada: 0 };
	
const m = { brazos: 0.18, busto: 0.29, cintura: 0.3, abdomen: 0.33, cadera: 0.26, muslos: 0.43, papada: 0 };

const limites = { brazos: 4, busto: 5, cintura: 10, abdomen: 10, cadera: 15, muslos: 15, papada: 2 };

const tamanioMujer = {ancho:92, alto:274}

var tSilueta;
var tTanga;
var tBra;

var pBrazos;
var pBusto;
var pCintura;
var pAbdomen;
var pCadera;
var pMuslos;

//niveles = { brazos: 0, busto: 0, cintura: 0, abdomen: 0, cadera: 0, muslos: 0, papada: 0 };
function actualizaVertices( niveles ) {
	//Solo funciona con nodos de tipo C
    tSilueta = JSON.parse(JSON.stringify(SILUETA));
    tTanga = JSON.parse(JSON.stringify(TANGA));
    tBra = JSON.parse(JSON.stringify(BRA));

    for (const [zona, intensidad] of Object.entries(niveles)) {
        if (MAPA_CORPORAL_SILUETA[zona]) {
            MAPA_CORPORAL_SILUETA[zona].forEach(config => {
                let i = 0;
                let desplazamiento = intensidad * config.lado * config.fuerza;
                
				i = tSilueta.findIndex(obj => obj.id === config.id);
				if (tSilueta[i]) {
					tSilueta[i].pc2.x += desplazamiento;
					tSilueta[i].fin.x += desplazamiento;
				}
				if (tSilueta[i + 1]) {
					tSilueta[i + 1].pc1.x += desplazamiento;
				}
            });
        }
		if (MAPA_CORPORAL_TANGA[zona]) {
            MAPA_CORPORAL_TANGA[zona].forEach(config => {
                let i = 0;
                let desplazamiento = intensidad * config.lado * config.fuerza;
                
				i = tTanga.findIndex(obj => obj.id === config.id);
				if (tTanga[i]) {
					tTanga[i].pc2.x += desplazamiento;
					tTanga[i].fin.x += desplazamiento;
				}
				if (tTanga[i + 1]) {
					tTanga[i + 1].pc1.x += desplazamiento;
				}
            });
        }
        if (MAPA_CORPORAL_BRA[zona]) {
            MAPA_CORPORAL_BRA[zona].forEach(config => {
                let i = 0;
                let desplazamiento = intensidad * config.lado * config.fuerza;
                
				i = tBra.findIndex(obj => obj.id === config.id);
				if (tBra[i]) {
					tBra[i].pc2.x += desplazamiento;
					tBra[i].fin.x += desplazamiento;
				}
				if (tBra[i + 1]) {
					tBra[i + 1].pc1.x += desplazamiento;
				}
            });
        }
    }
    
    //Posiciones para los trazos de líneas informativas
    pBrazos = {x0:tSilueta[103].fin.x, y0:tSilueta[103].fin.y, x1:(tSilueta[121].fin.x + tSilueta[122].fin.x)/2, y1:tSilueta[103].fin.y};
    pBusto = {x0:tSilueta[36].fin.x, y0:tSilueta[36].fin.y, x1:tSilueta[101].fin.x, y1:tSilueta[36].fin.y};
	pCintura = {x0:tSilueta[38].fin.x, y0:tSilueta[38].fin.y, x1:tSilueta[99].fin.x, y1:tSilueta[38].fin.y};
	pAbdomen = {x0:tSilueta[39].fin.x, y0:tSilueta[39].fin.y, x1:tSilueta[98].fin.x, y1:tSilueta[39].fin.y};
	pCadera = {x0:tSilueta[40].fin.x, y0:tSilueta[40].fin.y, x1:tSilueta[97].fin.x, y1:tSilueta[40].fin.y};
	pMuslos = {x0:tSilueta[95].fin.x, y0:tSilueta[95].fin.y, x1:(tSilueta[70].fin.x + tSilueta[71].fin.x)/2, y1:tSilueta[95].fin.y};
}

function obtenerTamanoLogicoCanvas(contexto) {
	const transform = typeof contexto.getTransform === 'function' ? contexto.getTransform() : null;
	const ratioX = transform && transform.a ? Math.abs(transform.a) : 1;
	const ratioY = transform && transform.d ? Math.abs(transform.d) : 1;

	return {
		width: contexto.canvas.width / (ratioX || 1),
		height: contexto.canvas.height / (ratioY || 1),
	};
}

function dibujaSilueta(contexto, escala) {
	const canvasLogico = obtenerTamanoLogicoCanvas(contexto);
	//Obtener el centro relativo del modelo respecto al canvas
	const inicioSilueta = {
		x: canvasLogico.width/2 - tamanioMujer.ancho*escala/2,
		y: canvasLogico.height/2 - tamanioMujer.alto*escala/2
	};
	
    contexto.clearRect(0, 0, canvasLogico.width, canvasLogico.height);
    contexto.fillStyle = "#FDF5E6";
    contexto.fillRect(0, 0, canvasLogico.width, canvasLogico.height);
    contexto.save();
    contexto.translate(inicioSilueta.x, inicioSilueta.y); // Centrar en el canvas
    contexto.scale(escala, escala);

	traza(contexto, CABELLO_FONDO, "#59110A", "#9A4121", 1/escala);
	traza(contexto, tSilueta, "#C49268", "#EEE8B9", 1/escala);
    traza(contexto, tTanga, "#D4176F", "#F8ABAD", 1/escala);
    traza(contexto, tBra, "#D4176F", "#F8ABAD", 1/escala);
    traza(contexto, CABELLO_FRENTE, "#59110A", "#9A4121", 1/escala);
    traza(contexto, BARBILLA, "#C49268", "#EEE8B9", 1/escala);
    traza(contexto, OMBLIGO, "#C49268", "#EEE8B9", 1/escala);
    traza(contexto, ANTEBRAZO_IZQ, "#C49268", "#EEE8B9", 1/escala);
    traza(contexto, ANTEBRAZO_DER, "#C49268", "#EEE8B9", 1/escala);
        
    contexto.restore();
}

function traza(contexto, vertices, colorTrazo, colorRelleno, anchoLinea) {
	if (vertices == undefined) {
		console.log("Invocación traza() sin vertices");
		return;
	}
	contexto.beginPath();
    vertices.forEach(p => {
        if (p.tipo === 'M') contexto.moveTo(p.x, p.y);
        else if (p.tipo === 'L') contexto.lineTo(p.x, p.y); 
        else if (p.tipo === 'C') contexto.bezierCurveTo(p.pc1.x, p.pc1.y, p.pc2.x, p.pc2.y, p.fin.x, p.fin.y);
    });
    contexto.fillStyle = colorRelleno;
    contexto.fill();
    contexto.strokeStyle = colorTrazo;
    contexto.lineWidth = anchoLinea;
    contexto.stroke();
}

export class Medidas {
  constructor(brazos, busto, cintura, abdomen, cadera, muslos, papada) {
    this.brazos = brazos;
    this.busto = busto;
    this.cintura = cintura;
    this.abdomen = abdomen;
    this.cadera = cadera;
    this.muslos = muslos;
    this.papada = papada;
  }
}

function dibujarLineaConTexto(contexto, escala, x1, y1, x2, y2, texto, posicionTexto, colorLinea, colorTexto, margenes) {
	const canvasLogico = obtenerTamanoLogicoCanvas(contexto);
	//Obtener el centro relativo del modelo respecto al canvas
	const inicioSilueta = {
		x: canvasLogico.width/2 - tamanioMujer.ancho*escala/2,
		y: canvasLogico.height/2 - tamanioMujer.alto*escala/2
	};
	
	contexto.save(); // Guarda el estado actual del canvas
	contexto.translate(inicioSilueta.x, inicioSilueta.y); // Centrar en el canvas
    contexto.scale(escala, escala);
    
	// 1. Dibujar la línea entrecortada
	contexto.beginPath();
	contexto.setLineDash([2, 1.5]);
	contexto.moveTo(x1, y1);
	contexto.lineTo(x2, y2);
	contexto.strokeStyle = colorLinea;
	contexto.stroke();

	// 2. Configurar estilo de texto
	contexto.font = "10px sans-serif";
	contexto.fillStyle = colorTexto;
  
	// Centramos verticalmente el texto respecto a la línea
	contexto.textBaseline = "middle"; 

	// 3. Posicionamiento del texto según el parámetro
	let textoX;
	const margen = 10 * margenes; // Espacio entre la línea y el texto

	if (posicionTexto === "izquierda") {
	contexto.textAlign = "right"; // El texto se apoya a la derecha de su X
	textoX = x1 - margen;    // Se desplaza a la izquierda del punto de inicio
	} else {
	contexto.textAlign = "left";  // El texto se apoya a la izquierda de su X
	textoX = x2 + margen;    // Se desplaza a la derecha del punto final
	}

	// 4. Dibujar el texto en la coordenada Y promedio de la línea
	const centerY = (y1 + y2) / 2;
	contexto.fillText(texto, textoX, centerY);

	contexto.restore(); // Restaura el canvas (quita el lineDash y estilos)
}
  

//Este es el componente, recibe el contexto 2D y las medidas anterior y actual en una instancia de clase
export function dibujaMujer (contexto, escala, mAnterior, mActual) {
	if (!(mAnterior instanceof Medidas && mActual instanceof Medidas)) {
		throw new Error("Se esperaba un objeto de tipo Medidas");
	}
	
	// Convertir las medidas en cm a mis unidades de dibujo
	let medidasAct = {
		brazos: m.brazos * (mActual.brazos - medidasBaseCM.brazos),
		busto: m.busto * (mActual.busto - medidasBaseCM.busto),
		cintura: m.cintura * (mActual.cintura - medidasBaseCM.cintura),
		abdomen: m.abdomen * (mActual.abdomen - medidasBaseCM.abdomen),
		cadera: m.cadera * (mActual.cadera - medidasBaseCM.cadera),
		muslos: m.muslos * (mActual.muslos - medidasBaseCM.muslos),
		papada: m.papada * (mActual.papada - medidasBaseCM.papada)
	};
	
	//Ajustar las medidas actuales con los limites
	for (const [zona, unidades] of Object.entries(medidasAct)) {
		medidasAct[zona] = medidasAct[zona] > limites[zona] ? limites[zona] : medidasAct[zona];
	}
	
	actualizaVertices(medidasAct);
	dibujaSilueta(contexto, escala);
	
	let colorDisminuyo = "#009648";
	let colorAumento = "#D4176F";
	let colorMantuvo = "#57267A";
	
	dibujaLeyendas(contexto, escala, colorDisminuyo, colorAumento, colorMantuvo);
	
	dibujarLineaConTexto(contexto, escala, pBrazos.x0, pBrazos.y0, pBrazos.x1, pBrazos.y1,
		"Brazo: " + mActual.brazos + " cm", "derecha", colorMantuvo, 
			mAnterior.brazos == 0 ? colorMantuvo :
				mAnterior.brazos - mActual.brazos > 0 ? colorDisminuyo :
					mAnterior.brazos - mActual.brazos < 0 ? colorAumento : colorMantuvo
		,1);
		
	dibujarLineaConTexto(contexto, escala, pBusto.x0, pBusto.y0, pBusto.x1, pBusto.y1,
		"Busto: " + mActual.busto + " cm", "izquierda", colorMantuvo, 
			mAnterior.busto == 0 ? colorMantuvo :
				mAnterior.busto - mActual.busto > 0 ? colorDisminuyo :
					mAnterior.busto - mActual.busto < 0 ? colorAumento : colorMantuvo
		,2);
	
	dibujarLineaConTexto(contexto, escala, pCintura.x0, pCintura.y0, pCintura.x1, pCintura.y1,
		"Cintura: " + mActual.cintura + " cm", "izquierda", colorMantuvo, 
			mAnterior.cintura == 0 ? colorMantuvo :
				mAnterior.cintura - mActual.cintura > 0 ? colorDisminuyo :
					mAnterior.cintura - mActual.cintura < 0 ? colorAumento : colorMantuvo
		,3);
		
	dibujarLineaConTexto(contexto, escala, pAbdomen.x0, pAbdomen.y0, pAbdomen.x1, pAbdomen.y1,
		"Abdomen: " + mActual.abdomen + " cm", "derecha", colorMantuvo, 
			mAnterior.abdomen == 0 ? colorMantuvo :
				mAnterior.abdomen - mActual.abdomen > 0 ? colorDisminuyo :
					mAnterior.abdomen - mActual.abdomen < 0 ? colorAumento : colorMantuvo
		,3);
		
	dibujarLineaConTexto(contexto, escala, pCadera.x0, pCadera.y0, pCadera.x1, pCadera.y1,
		"Cadera: " + mActual.cadera + " cm", "izquierda", colorMantuvo, 
			mAnterior.cadera == 0 ? colorMantuvo :
				mAnterior.cadera - mActual.cadera > 0 ? colorDisminuyo :
					mAnterior.cadera - mActual.cadera < 0 ? colorAumento : colorMantuvo
		,3);
		
	dibujarLineaConTexto(contexto, escala, pMuslos.x0, pMuslos.y0, pMuslos.x1, pMuslos.y1,
		"Muslo: " + mActual.muslos + " cm", "derecha", colorMantuvo, 
			mAnterior.muslos == 0 ? colorMantuvo :
				mAnterior.muslos - mActual.muslos > 0 ? colorDisminuyo :
					mAnterior.muslos - mActual.muslos < 0 ? colorAumento : colorMantuvo
		,5);
}

function dibujaLeyendas(contexto, escala, colorDisminuyo, colorAumento, colorMantuvo) {
	const canvasLogico = obtenerTamanoLogicoCanvas(contexto);
	const leyendas = [
	  { texto: "Aumentó", color: colorAumento },
	  { texto: "Se mantuvo", color: colorMantuvo },
	  { texto: "Disminuyó", color: colorDisminuyo }
	];

	// Configuración de dimensiones
	const tamañoCuadro = 10;
	const espacioEntreFilas = 3;
	const margenIzquierdo = 20;
	const margenInferior = 20;

	// Calcular la posición inicial (Y) para que quede abajo
	// Sumamos el alto de todos los cuadros y sus espacios
	const altoTotal = (tamañoCuadro * leyendas.length) + (espacioEntreFilas * (leyendas.length - 1));
	let startY = canvasLogico.height/escala - altoTotal - margenInferior;

	contexto.save(); // Guarda el estado actual del canvas
    contexto.scale(escala, escala);
    
	// Configurar el estilo del texto una sola vez
	contexto.font = "10px sans-serif";
	contexto.textBaseline = "middle";
	contexto.textAlign = "left";

	// Dibujar cada elemento
	leyendas.forEach((item) => {
	  // Limpiar cualquier trazo anterior para evitar cuadros entrecortados
	  contexto.setLineDash([]);
	  
	  // Dibujar el cuadro de color
	  contexto.fillStyle = item.color;
	  contexto.fillRect(margenIzquierdo, startY, tamañoCuadro, tamañoCuadro);
	  
	  // Dibujar el texto a la derecha del cuadro
	  contexto.fillStyle = "#1A1A1A"; // Color del texto oscuro
	  const textoX = margenIzquierdo + tamañoCuadro + 10; // Cuadro + margen
	  const textoY = startY + (tamañoCuadro / 2);        // Centro vertical del cuadro
	  
	  contexto.fillText(item.texto, textoX, textoY);
	  
	  // Bajar la posición para la siguiente fila
	  startY += tamañoCuadro + espacioEntreFilas;
	});

	contexto.restore(); // Restaura el canvas (quita el lineDash y estilos)
}
