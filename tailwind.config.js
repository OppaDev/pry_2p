import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    mode: "jit",
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    safelist: [
        'translate-x-0',
        '-translate-x-full',
        'max-h-0',
        'max-h-96',
        'opacity-0',
        'opacity-100',
        'rotate-180',
        'transform-dropdown',
        'transform-dropdown-show',
    ],

    darkMode: "class",

    theme: {
        screens: {
            sm: "576px",
            "sm-max": { max: "576px" },
            md: "768px",
            "md-max": { max: "768px" },
            lg: "992px",
            "lg-max": { max: "992px" },
            xl: "1200px",
            "xl-max": { max: "1200px" },
            "2xl": "1320px",
            "2xl-max": { max: "1320px" },
        },

        colors: ({ colors }) => ({
            inherit: colors.inherit,
            current: colors.current,
            transparent: colors.transparent,
            black: colors.black,
            white: colors.white,

            slate: {
                DEFAULT: colors.slate,
                50: "#f8fafc",
                100: "#dee2e6",
                200: "#cbd3da",
                300: "#a8b8d8",
                400: "#8392ab",
                500: "#67748e",
                600: "#627594",
                700: "#344767",
                800: "#3a416f",
                900: "#0f172a",
            },

            gray: {
                DEFAULT: colors.gray,
                50: "#f8f9fa",
                100: "#ebeff4",
                200: "#e9ecef",
                300: "#d2d6da",
                400: "#ced4da",
                500: "#adb5bd",
                600: "#6c757d",
                700: "#495057",
                800: "#252f40",
                900: "#141727",
            },

            zinc: {
                DEFAULT: colors.zinc,
                50: "#fafafa",
                100: "#f4f4f5",
                200: "#e4e4e7",
                300: "#d4d4d8",
                400: "#a1a1aa",
                500: "#71717a",
                600: "#52525b",
                700: "#3f3f46",
                800: "#27272a",
                900: "#18181b",
            },

            neutral: {
                DEFAULT: colors.neutral,
                50: "#fafafa",
                100: "#f5f5f5",
                200: "#e5e5e5",
                300: "#d4d4d4",
                400: "#a3a3a3",
                500: "#737373",
                600: "#525252",
                700: "#404040",
                800: "#262626",
                900: "#111111",
            },

            stone: {
                DEFAULT: colors.stone,
                50: "#fafaf9",
                100: "#f5f5f4",
                200: "#e7e5e4",
                300: "#d6d3d1",
                400: "#a8a29e",
                500: "#78716c",
                600: "#57534e",
                700: "#44403c",
                800: "#292524",
                900: "#1c1917",
            },

            red: {
                DEFAULT: colors.red,
                50: "#fef2f2",
                100: "#fee2e2",
                200: "#fecaca",
                300: "#fca5a5",
                400: "#f87171",
                500: "#f53939",
                600: "#ea0606",
                700: "#b91c1c",
                800: "#991b1b",
                900: "#7f1d1d",
            },

            orange: {
                DEFAULT: colors.orange,
                50: "#fff7ed",
                100: "#ffedd5",
                200: "#fed7aa",
                300: "#fdba74",
                400: "#fb923c",
                500: "#f97316",
                600: "#ea580c",
                700: "#c2410c",
                800: "#9a3412",
                900: "#7c2d12",
            },

            amber: {
                DEFAULT: colors.amber,
                50: "#fffbeb",
                100: "#fef3c7",
                200: "#fde68a",
                300: "#fcd34d",
                400: "#fbbf24",
                500: "#f59e0b",
                600: "#d97706",
                700: "#b45309",
                800: "#92400e",
                900: "#78350f",
            },

            yellow: {
                DEFAULT: colors.yellow,
                50: "#fefce8",
                100: "#fef9c3",
                200: "#fef08a",
                300: "#fde047",
                400: "#fbcf33",
                500: "#eab308",
                600: "#ca8a04",
                700: "#a16207",
                800: "#854d0e",
                900: "#713f12",
            },

            lime: {
                DEFAULT: colors.lime,
                50: "#f7fee7",
                100: "#ecfccb",
                200: "#d9f99d",
                300: "#bef264",
                400: "#98ec2d",
                500: "#82d616",
                600: "#65a30d",
                700: "#4d7c0f",
                800: "#3f6212",
                900: "#365314",
            },

            green: {
                DEFAULT: colors.green,
                50: "#f0fdf4",
                100: "#dcfce7",
                200: "#bbf7d0",
                300: "#86efac",
                400: "#4ade80",
                500: "#22c55e",
                600: "#17ad37",
                700: "#15803d",
                800: "#166534",
                900: "#14532d",
            },

            emerald: {
                DEFAULT: colors.emerald,
                50: "#ecfdf5",
                100: "#d1fae5",
                200: "#a7f3d0",
                300: "#6ee7b7",
                400: "#34d399",
                500: "#10b981",
                600: "#059669",
                700: "#047857",
                800: "#065f46",
                900: "#064e3b",
            },

            teal: {
                DEFAULT: colors.teal,
                50: "#f0fdfa",
                100: "#ccfbf1",
                200: "#99f6e4",
                300: "#5eead4",
                400: "#2dd4bf",
                500: "#14b8a6",
                600: "#0d9488",
                700: "#0f766e",
                800: "#115e59",
                900: "#134e4a",
            },

            cyan: {
                DEFAULT: colors.cyan,
                50: "#ecfeff",
                100: "#cffafe",
                200: "#a5f3fc",
                300: "#67e8f9",
                400: "#21d4fd",
                500: "#17c1e8",
                600: "#0891b2",
                700: "#0e7490",
                800: "#155e75",
                900: "#164e63",
            },

            sky: {
                DEFAULT: colors.sky,
                50: "#f0f9ff",
                100: "#e0f2fe",
                200: "#bae6fd",
                300: "#7dd3fc",
                400: "#38bdf8",
                500: "#0ea5e9",
                600: "#3ea1ec",
                700: "#0369a1",
                800: "#075985",
                900: "#0e456d",
            },

            blue: {
                DEFAULT: colors.blue,
                50: "#eff6ff",
                100: "#dbeafe",
                200: "#bfdbfe",
                300: "#93c5fd",
                400: "#60a5fa",
                500: "#3b82f6",
                600: "#2152ff",
                700: "#1d4ed8",
                800: "#344e86",
                900: "#00007d",
            },

            indigo: {
                DEFAULT: colors.indigo,
                50: "#eef2ff",
                100: "#e0e7ff",
                200: "#c7d2fe",
                300: "#a5b4fc",
                400: "#818cf8",
                500: "#6366f1",
                600: "#4f46e5",
                700: "#4338ca",
                800: "#3730a3",
                900: "#312e81",
            },

            violet: {
                DEFAULT: colors.violet,
                50: "#f5f3ff",
                100: "#ede9fe",
                200: "#ddd6fe",
                300: "#c4b5fd",
                400: "#a78bfa",
                500: "#8b5cf6",
                600: "#7c3aed",
                700: "#6d28d9",
                800: "#5b21b6",
                900: "#4c1d95",
            },

            purple: {
                DEFAULT: colors.purple,
                50: "#faf5ff",
                100: "#f3e8ff",
                200: "#e9d5ff",
                300: "#d8b4fe",
                400: "#c084fc",
                500: "#a855f7",
                600: "#9333ea",
                700: "#7928ca",
                800: "#6b21a8",
                900: "#581c87",
            },

            fuchsia: {
                DEFAULT: colors.fuchsia,
                50: "#fdf4ff",
                100: "#fae8ff",
                200: "#f5d0fe",
                300: "#e293d3",
                400: "#e879f9",
                500: "#cb0c9f",
                600: "#c026d3",
                700: "#a21caf",
                800: "#830866",
                900: "#701a75",
            },

            pink: {
                DEFAULT: colors.pink,
                50: "#fdf2f8",
                100: "#fce7f3",
                200: "#fbcfe8",
                300: "#f9a8d4",
                400: "#f472b6",
                500: "#ff0080",
                600: "#db2777",
                700: "#be185d",
                800: "#9d174d",
                900: "#831843",
            },

            rose: {
                DEFAULT: colors.rose,
                50: "#fff1f2",
                100: "#ffe4e6",
                200: "#fecdd3",
                300: "#fda4af",
                400: "#ff667c",
                500: "#f43f5e",
                600: "#e11d48",
                700: "#be123c",
                800: "#9f1239",
                900: "#881337",
            },
        }),

        spacing: {
            px: "1px",
            0: "0px",
            0.38: "0.095rem",
            0.5: "0.125rem",
            0.54: "0.135rem",
            0.75: "0.1875rem",
            1: "0.25rem",
            1.2: "0.3rem",
            1.25: "0.3125rem",
            1.4: "0.35rem",
            1.5: "0.375rem",
            1.6: "0.4rem",
            1.75: "0.4375rem",
            1.8: "0.45rem",
            2: "0.5rem",
            2.2: "0.55rem",
            2.375: ".59375rem",
            2.5: "0.625rem",
            2.7: "0.675rem",
            3: "0.75rem",
            3.5: "0.875rem",
            3.6: "0.9rem",
            4: "1rem",
            4.5: "1.125rem",
            4.92: "1.23rem",
            5: "1.25rem",
            5.25: "1.3125rem",
            5.5: "1.375rem",
            5.6: "1.4rem",
            5.75: "1.4375rem",
            6: "1.5rem",
            6.35: "1.5875rem",
            6.5: "1.625rem",
            6.92: "1.73rem",
            7: "1.75rem",
            7.5: "1.875rem",
            8: "2rem",
            8.75: "2.1875rem",
            9: "2.25rem",
            10: "2.5rem",
            11: "2.75rem",
            11.252: "2.813rem",
            12: "3rem",
            14: "3.5rem",
            16: "4rem",
            18.5: "4.625rem",
            19.5: "4.875rem",
            20: "5rem",
            24: "6rem",
            28: "7rem",
            30: "7.5rem",
            32: "8rem",
            34: "8.5rem",
            36: "9rem",
            40: "10rem",
            44: "11rem",
            48: "12rem",
            52: "13rem",
            54: "13.5rem",
            56: "14rem",
            60: "15rem",
            62.5: "15.625rem",
            64: "16rem",
            68: "17rem",
            68.5: "17.125rem",
            72: "18rem",
            75: "18.75rem",
            80: "20rem",
            90: "22.5rem",
            96: "24rem",
            120: "30rem",
            135: "33.75rem", //sm
            180: "45rem", //md
            240: "60rem", //lg
            285: "71.25rem", //xl
            330: "82.5rem", //xxl
            "31/100": "31%",
        },

        extend: {
            fontFamily: {
                sans: ['Open Sans', ...defaultTheme.fontFamily.sans],
                serif: ['SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace', "serif"],
                body: ["Roboto", "sans-serif"],
                awesome: ["FontAwesome"],
            },

            animation: {
                none: "none",
                spin: "spin 1s linear infinite",
                ping: "ping 1s cubic-bezier(0, 0, 0.2, 1) infinite",
                pulse: "pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite",
                bounce: "bounce 1s infinite",
                "fade-up": "fade-up 1.5s both",
            },

            keyframes: {
                "fade-up": {
                    from: {
                        opacity: "0",
                        transform: "translateY(100%)",
                    },
                    to: {
                        opacity: "1",
                    },
                },
                spin: {
                    to: {
                        transform: "rotate(360deg)",
                    },
                },
                ping: {
                    "75%, 100%": {
                        transform: "scale(2)",
                        opacity: "0",
                    },
                },
                pulse: {
                    "50%": {
                        opacity: ".5",
                    },
                },
                bounce: {
                    "0%, 100%": {
                        transform: "translateY(-25%)",
                        animationTimingFunction: "cubic-bezier(0.8,0,1,1)",
                    },
                    "50%": {
                        transform: "none",
                        animationTimingFunction: "cubic-bezier(0,0,0.2,1)",
                    },
                },
            },

            boxShadow: {
                "soft-xxs": "0 1px 5px 1px #ddd",
                "soft-xs": "0 3px 5px -1px rgba(0,0,0,.09),0 2px 3px -1px rgba(0,0,0,.07)",
                "soft-sm": "0 .25rem .375rem -.0625rem hsla(0,0%,8%,.12),0 .125rem .25rem -.0625rem hsla(0,0%,8%,.07)",
                "soft-md": "0 4px 7px -1px rgba(0,0,0,.11),0 2px 4px -1px rgba(0,0,0,.07)",
                "soft-lg": "0 2px 12px 0 rgba(0,0,0,.16)",
                "soft-xl": "0 20px 27px 0 rgba(0,0,0,0.05)",
                "soft-2xl": "0 .3125rem .625rem 0 rgba(0,0,0,.12)",
                "soft-3xl": "0 8px 26px -4px hsla(0,0%,8%,.15),0 8px 9px -5px hsla(0,0%,8%,.06)",
                "soft-primary-outline": "0 0 0 2px #e9aede",
                blur: "inset 0 0 1px 1px hsla(0,0%,100%,.9),0 20px 27px 0 rgba(0,0,0,.05)",
            },

            backgroundImage: {
                "gradient-fuchsia": "linear-gradient(310deg, #7928ca, #ff0080)",
                "gradient-cyan": "linear-gradient(310deg, #2152ff, #21d4fd)",
                "gradient-orange": "linear-gradient(310deg, #f53939, #fbcf33)",
                "gradient-red": "linear-gradient(310deg, #ea0606, #ff667c)",
                "gradient-lime": "linear-gradient(310deg, #17ad37, #98ec2d)",
                "gradient-slate": "linear-gradient(310deg, #627594, #a8b8d8)",
                "gradient-dark-gray": "linear-gradient(310deg, #141727, #3a416f)",
                "gradient-gray": "linear-gradient(310deg, #ced4da, #ebeff4)",
                "gradient-horizontal-dark": "linear-gradient(90deg,transparent,rgba(0,0,0,.4),transparent)",
                "gradient-horizontal-light": "linear-gradient(90deg,transparent,rgba(0,0,0,.1),transparent)",
            },

            backgroundSize: {
                150: "150%",
            },

            borderRadius: {
                xs: "0.0625rem",
                "3.5xl": "1.875rem",
                circle: "50%",
                blur: "40px",
            },

            container: {
                center: true,
                padding: "1.5rem",
                screens: {
                    sm: "540px",
                    md: "720px",
                    lg: "960px",
                    xl: "1140px",
                    "2xl": "1320px",
                },
            },

            fontSize: {
                "3xs": ["0.5rem", { lineHeight: "1rem" }],
                xxs: ["0.65rem", { lineHeight: "1rem" }],
                "banner-calculate": ["calc(1.625rem+4.5vw)"],
            },

            height: {
                sidenav: "calc(100vh - 370px)",
            },

            letterSpacing: {
                "tight-soft": "-0.025rem",
                tighter: "-0.05em",
            },

            lineHeight: {
                none: "1",
                tighter: "1.2",
                button: "1.3",
                pro: "1.4",
                default: "1.6",
            },

            maxWidth: {
                sidebar: "15.625rem",
                "62.5": "15.625rem",
            },

            minHeight: {
                "85-screen": "85vh",
                "75-screen": "75vh",
                "50-screen": "50vh",
            },

            opacity: {
                12.5: "0.125",
                65: "0.65",
            },

            rotate: {
                1: "1deg",
                2: "2deg",
                3: "3deg",
                6: "6deg",
                10: "10deg",
                12: "12deg",
            },

            scale: {
                102: "1.02",
            },

            transformOrigin: {
                "10-10": "10% 10%",
                "10-90": "10% 90%",
            },

            transitionDuration: {
                250: "250ms",
                350: "350ms",
                600: "600ms",
            },

            transitionTimingFunction: {
                soft: "cubic-bezier(0.25, 0.1, 0.25, 1)",
                "soft-in": "cubic-bezier(0.42, 0, 1, 1)",
                "soft-in-out": "cubic-bezier(0.42, 0, 0.58, 1)",
                "soft-out": "cubic-bezier(0, 0, 0.58, 1)",
                bounce: "cubic-bezier(0.34, 1.61, 0.7, 1.3)",
                "ease-nav-brand": "cubic-bezier(0.25, 0.1, 0.25, 1)",
            },

            width: {
                "1/100": "1%",
                "3/10": "30%",
                "9/10": "90%",
            },

            zIndex: {
                990: "990",
                sticky: "1020",
            },
        },
    },

    variants: {
        display: ["responsive", "dropdown"],
    },

    plugins: [
        forms,
        
        function({ addComponents, addUtilities }) {
            // Utilidades específicas de Soft UI
            addUtilities({
                ".transform3d": {
                    transform: "perspective(999px) rotateX(0deg) translateZ(0)",
                },
                ".transform3d-hover": {
                    transform: "perspective(999px) rotateX(7deg) translate3d(0,-4px,5px)",
                },
                ".transform-dropdown": {
                    transform: "perspective(999px) rotateX(-10deg) translateZ(0) translate3d(0,37px,0)",
                },
                ".transform-dropdown-show": {
                    transform: "perspective(999px) rotateX(0deg) translateZ(0) translate3d(0,37px,5px)",
                },
                ".flex-wrap-inherit": {
                    "flex-wrap": "inherit",
                },
                ".ease-nav-brand": {
                    "transition-timing-function": "cubic-bezier(0.25, 0.1, 0.25, 1)",
                },
                ".ease-soft": {
                    "transition-timing-function": "cubic-bezier(0.25, 0.1, 0.25, 1)",
                },
            });

            // Componentes tipográficos de Soft UI
            const typography = {
                a: {
                    "letter-spacing": "-0.025rem",
                },

                hr: {
                    margin: "1rem 0",
                    border: "0",
                    opacity: ".25",
                },

                img: {
                    maxWidth: "none",
                },

                label: {
                    display: "inline-block",
                },

                p: {
                    "line-height": "1.625",
                    "font-weight": "400",
                    "margin-bottom": "1rem",
                },

                small: {
                    "font-size": ".875em",
                },

                svg: {
                    display: "inline",
                },

                table: {
                    "border-collapse": "inherit",
                },

                "h1, h2, h3, h4, h5, h6": {
                    "margin-bottom": ".5rem",
                    color: "#344767",
                },

                "h1, h2, h3, h4": {
                    "letter-spacing": "-0.05rem",
                },

                "h1, h2, h3": {
                    "font-weight": "700",
                },
                "h4, h5, h6": {
                    "font-weight": "600",
                },

                h1: {
                    "font-size": "3rem",
                    "line-height": "1.25",
                },
                h2: {
                    "font-size": "2.25rem",
                    "line-height": "1.3",
                },
                h3: {
                    "font-size": "1.875rem",
                    "line-height": "1.375",
                },
                h4: {
                    "font-size": "1.5rem",
                    "line-height": "1.375",
                },
                h5: {
                    "font-size": "1.25rem",
                    "line-height": "1.375",
                },
                h6: {
                    "font-size": "1rem",
                    "line-height": "1.625",
                },
            };
            addComponents(typography);
        },

        // Plugin para gradientes con ángulos personalizados
        function({ matchUtilities, theme }) {
            matchUtilities({
                "bg-gradient": (angle) => ({
                    "background-image": `linear-gradient(${angle}, var(--tw-gradient-stops))`,
                }),
            });
        }
    ],
};