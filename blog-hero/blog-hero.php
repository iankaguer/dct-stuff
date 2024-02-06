<?php

function blogHero()
{
    // retrieve 3 most recent posts having cover images
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 3,
        'meta_query' => array(
            array(
                'key' => '_thumbnail_id',
            ),
        ),
    );
    $recent_posts = new WP_Query($args);

    ob_start(); // start output buffering
    ?>
    <style>
        .blog-hero {
            background-color: #f4f4f4;
            width: 100%;
            height: 80vh;
            margin: 0;
        }

        .blog-hero__container {
            position: relative;
            overflow: hidden;
            height: 80vh;
            width: 100%;
            margin: 0;
        }

        .blog-hero__posts {
            display: flex;
            transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            width: 100%;
            margin: 0;
        }

        .blog-hero__post {
            flex: 0 0 calc(100%);
            display: flex;
            height: 80vh;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            background-color: #f0f0f0;
            background-size: cover;
            margin: 0;
            position: relative;
            background-position: center;
        }

        .blog-hero__post-inner {
            background-color: rgba(2, 34, 70, 0.9);
            padding: 1rem;
            color: #d5d5d5;
            text-align: center;
            transition: 0.3s;
            position: absolute;
            width: 100%;
            bottom: 0;
            height: 100%;
        }

        .blog-hero__post-title {
            font-size: 1.5em;
            margin-bottom: 10px;
            color: white;
        }

        .blog-hero__post-date {
            font-size: 0.8em;
            color: #ff8702;
        }

        .blog-hero__controls {
            position: absolute;
            bottom: 5%;
            right: 5%;
            transform: translateY(-50%);
            font-size: 2em;
            color: white;
            cursor: pointer;
            transition: 0.3s;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 3;
            display: flex;
        }

        .prev, .next {
            z-index: 2;
            width: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .prev {
            border-right: 1px solid #3a3a3a;
        }

        .blog-hero__post-link {
            text-decoration: none;
            display: flex;
            width: 80%;
            height: 100%;
            gap: 1em;
            align-items: center;
            justify-content: flex-end;
            flex-direction: row;
            text-align: left;
        }

        .blog-hero__post-img {
            flex: 0 0 40%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 35vh;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        /** for tablet and mobile **/
        @media (max-width: 768px) {
            .blog-hero__post-link {
                flex-direction: column;
                width: 100%;
            }
            .blog-hero__post-img {
                flex: 0 0 100%;
                height: 50vh;
                width: 100%;
            }
        }

    </style>

    <section class="blog-hero" id="blog-hero__slider">
        <div class="blog-hero__container">
            <div class="blog-hero__posts" id="blog-hero__slide">
                <?php
                if ($recent_posts->have_posts()) :
                    while ($recent_posts->have_posts()) : $recent_posts->the_post();

                        ?>


                        <div class="blog-hero__post"
                             style="background-image: url(<?php echo get_the_post_thumbnail_url(); ?>)">
                            <div class="blog-hero__post-inner">
                                <a href="<?php the_permalink(); ?>" class="blog-hero__post-link">
                                    <div class="blog-hero__post-img"
                                         style="background-image: url(<?php echo get_the_post_thumbnail_url(); ?>)">

                                    </div>
                                    <div>
                                        <h3 class="blog-hero__post-title"><?php the_title(); ?></h3>
                                        <p class="blog-hero__post-date"><?php the_date(); ?></p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php
                    endwhile;
                endif;
                ?>
            </div>
            <div class="blog-hero__controls">
                <div id="prev" class="blog-hero__control prev">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#fff">
                        <path d="M0 0h24v24H0V0z" fill="none" opacity=".87"/>
                        <path d="M17.51 3.87L15.73 2.1 5.84 12l9.9 9.9 1.77-1.77L9.38 12l8.13-8.13z"/>
                    </svg>
                </div>
                <div id="next" class="blog-hero__control next">
                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px"
                         viewBox="0 0 24 24" width="24px"
                         fill="#fff">
                        <g>
                            <path d="M0,0h24v24H0V0z" fill="none"/>
                        </g>
                        <g>
                            <polygon points="6.23,20.23 8,22 18,12 8,2 6.23,3.77 14.46,12"/>
                        </g>
                    </svg>
                </div>
            </div>

        </div>
    </section>
    <script>
		function docReady(fn) {
			// see if DOM is already available
			if (document.readyState === 'complete' || document.readyState === 'interactive') {
				// call on next available tick
				setTimeout(fn, 1);
			} else {
				document.addEventListener('DOMContentLoaded', fn);
			}
		}

		docReady(function () {
			// Wrap the code in an Immediately Invoked Function Expression (IIFE) to avoid polluting the global namespace.
			(function () {
				// Get a reference to the slider element from the DOM using its ID.
				const slider = document.getElementById('blog-hero__slide');
				// Get the child elements of the slider, which represent the slides.
				const slides = slider.children;

				// Get a reference to the previous and next buttons.
                const prev = document.getElementById('prev');
                const next = document.getElementById('next');

				// Get the number of slides.
                const totalSlides = slides.length;

				// Set the initial slide index.
                let currentSlide = 0;

				// Set the initial position of the slider.
                slider.style.transform = `translateX(-${currentSlide * 100}%)`;

				// Function to move to the next slide, and update the current slide index.
                // If the current slide is the last slide, move to the first slide.
                function nextSlide() {
                    currentSlide = (currentSlide + 1) % totalSlides;
                    slider.style.transform = `translateX(-${currentSlide * 100}%)`;
                }


				//each 8 seconds the slider will change
                let interval = setInterval(() => {
                    nextSlide();
                }, 8000);

				// Function to move to the previous slide, and update the current slide index.
                // If the current slide is the first slide, move to the last slide.
                function prevSlide() {
                    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                    slider.style.transform = `translateX(-${currentSlide * 100}%)`;
                }

				// Add event listeners for the previous and next buttons.
                prev.addEventListener('click', () => {
                    clearInterval(interval);
                    prevSlide();
                });
                next.addEventListener('click', () => {
                    clearInterval(interval);
                    nextSlide();
                });




			})();


		});
    </script>


    <?php
    return ob_get_clean(); // return the buffered content
}

add_shortcode('blogHero', 'blogHero');
