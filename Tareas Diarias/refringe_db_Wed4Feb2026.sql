--
-- PostgreSQL database dump
--

--\restrict eJx1WDI8Xw9djxzUh7kBcYIAbBJSvN6SO9kBjoktSccMLnsa22vOd1Dr45fmfFm

-- Dumped from database version 13.23
-- Dumped by pg_dump version 13.23

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: update_updated_date_time_column(); Type: FUNCTION; Schema: public; Owner: wwwolamy
--

CREATE FUNCTION public.update_updated_date_time_column() RETURNS trigger
    LANGUAGE plpgsql
    AS $$BEGIN
    NEW.updated_date_time= CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$;


--ALTER FUNCTION public.update_updated_date_time_column() OWNER TO wwwolamy;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: Genres; Type: TABLE; Schema: public; Owner: wwwolamy
--

CREATE TABLE public."Genres" (
    "ID" integer NOT NULL,
    name character varying(100),
    created_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--ALTER TABLE public."Genres" OWNER TO wwwolamy;

--
-- Name: COLUMN "Genres"."ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."Genres"."ID" IS 'PK';


--
-- Name: Proposal_details; Type: TABLE; Schema: public; Owner: wwwolamy
--

CREATE TABLE public."Proposal_details" (
    "ID" integer NOT NULL,
    "Users_ID" integer,
    title character varying(255),
    "Location_ID" integer,
    created_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    question_classes jsonb DEFAULT '[]'::jsonb,
    "Venues_ID" integer
);


--ALTER TABLE public."Proposal_details" OWNER TO wwwolamy;

--
-- Name: COLUMN "Proposal_details"."ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."Proposal_details"."ID" IS 'PK';


--
-- Name: COLUMN "Proposal_details"."Users_ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."Proposal_details"."Users_ID" IS 'FK';


--
-- Name: COLUMN "Proposal_details"."Location_ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."Proposal_details"."Location_ID" IS 'FK';


--
-- Name: Question_classes; Type: TABLE; Schema: public; Owner: wwwolamy
--

CREATE TABLE public."Question_classes" (
    "ID" integer NOT NULL,
    name character varying(100) NOT NULL,
    created_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--ALTER TABLE public."Question_classes" OWNER TO wwwolamy;

--
-- Name: COLUMN "Question_classes"."ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."Question_classes"."ID" IS 'PK';


--
-- Name: Questions; Type: TABLE; Schema: public; Owner: wwwolamy
--

CREATE TABLE public."Questions" (
    "ID" integer NOT NULL,
    "Question_classes_ID" integer NOT NULL,
    "Proposal_details_ID" integer DEFAULT 0 NOT NULL,
    questions_answers jsonb DEFAULT '[]'::jsonb,
    notes text,
    created_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--ALTER TABLE public."Questions" OWNER TO wwwolamy;

--
-- Name: COLUMN "Questions"."ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."Questions"."ID" IS 'PK';


--
-- Name: COLUMN "Questions"."Question_classes_ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."Questions"."Question_classes_ID" IS 'fK';


--
-- Name: COLUMN "Questions"."Proposal_details_ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."Questions"."Proposal_details_ID" IS 'fK';


--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: wwwolamy
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 2147483647
    CACHE 1;


--ALTER TABLE public.roles_id_seq OWNER TO wwwolamy;

--
-- Name: Roles; Type: TABLE; Schema: public; Owner: wwwolamy
--

CREATE TABLE public."Roles" (
    "ID" integer DEFAULT nextval('public.roles_id_seq'::regclass) NOT NULL,
    name character varying(255),
    created_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--ALTER TABLE public."Roles" OWNER TO wwwolamy;

--
-- Name: Screenfield_validation; Type: TABLE; Schema: public; Owner: wwwolamy
--

CREATE TABLE public."Screenfield_validation" (
    "ID" integer NOT NULL,
    rule character varying(100),
    regex character varying(255),
    "errMsg" character varying(255),
    created_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--ALTER TABLE public."Screenfield_validation" OWNER TO wwwolamy;

--
-- Name: TABLE "Screenfield_validation"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON TABLE public."Screenfield_validation" IS 'Rules, Regex, error messages for screen-field validation';


--
-- Name: COLUMN "Screenfield_validation"."ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."Screenfield_validation"."ID" IS 'PK';


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: wwwolamy
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 2147483647
    CACHE 1;


--ALTER TABLE public.users_id_seq OWNER TO wwwolamy;

--
-- Name: Users; Type: TABLE; Schema: public; Owner: wwwolamy
--

CREATE TABLE public."Users" (
    "ID" integer DEFAULT nextval('public.users_id_seq'::regclass) NOT NULL,
    "Roles_ID" integer NOT NULL,
    first_name character varying(100) NOT NULL,
    last_name character varying(100) NOT NULL,
    street character varying(255),
    city character varying(100),
    state character varying(50),
    zip character varying(10),
    phone character varying(12) NOT NULL,
    email character varying(255),
    username character varying(50),
    password text,
    created_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    notes text
);


--ALTER TABLE public."Users" OWNER TO wwwolamy;

--
-- Name: COLUMN "Users"."ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."Users"."ID" IS 'PK';


--
-- Name: COLUMN "Users"."Roles_ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."Users"."Roles_ID" IS 'FK';


--
-- Name: venues_id_seq; Type: SEQUENCE; Schema: public; Owner: wwwolamy
--

CREATE SEQUENCE public.venues_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 2147483647
    CACHE 1;


--ALTER TABLE public.venues_id_seq OWNER TO wwwolamy;

--
-- Name: Venues; Type: TABLE; Schema: public; Owner: wwwolamy
--

CREATE TABLE public."Venues" (
    "ID" integer DEFAULT nextval('public.venues_id_seq'::regclass) NOT NULL,
    name character varying(255),
    questions_answers jsonb DEFAULT '[]'::jsonb,
    created_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    venue_check boolean DEFAULT false NOT NULL
);


--ALTER TABLE public."Venues" OWNER TO wwwolamy;

--
-- Name: genres_id_seq; Type: SEQUENCE; Schema: public; Owner: wwwolamy
--

CREATE SEQUENCE public.genres_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--ALTER TABLE public.genres_id_seq OWNER TO wwwolamy;

--
-- Name: genres_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: wwwolamy
--

ALTER SEQUENCE public.genres_id_seq OWNED BY public."Genres"."ID";


--
-- Name: orphan_proposal_details_view; Type: VIEW; Schema: public; Owner: wwwolamy
--

CREATE VIEW public.orphan_proposal_details_view AS
 SELECT "Proposal_details"."ID",
    "Proposal_details"."Users_ID",
    "Proposal_details".title,
    "Proposal_details"."Location_ID",
    "Proposal_details".question_classes,
    "Proposal_details".created_date_time,
    "Proposal_details".updated_date_time
   FROM public."Proposal_details"
  WHERE (NOT (EXISTS ( SELECT "Users"."ID"
           FROM public."Users"
          WHERE ("Users"."ID" = "Proposal_details"."Users_ID"))));


--ALTER TABLE public.orphan_proposal_details_view OWNER TO wwwolamy;

--
-- Name: orphan_questions_of_proposal_details_view; Type: VIEW; Schema: public; Owner: wwwolamy
--

CREATE VIEW public.orphan_questions_of_proposal_details_view AS
 SELECT q."ID" AS "Questions_ID",
    q."Question_classes_ID",
    q."Proposal_details_ID",
    q.questions_answers
   FROM public."Questions" q
  WHERE ((NOT (EXISTS ( SELECT 1
           FROM public."Proposal_details" p,
            LATERAL jsonb_array_elements(p.question_classes) jsonb_element(value)
          WHERE (((jsonb_element.value ->> 'ID'::text) = (q."Question_classes_ID")::text) AND (p."ID" = q."Proposal_details_ID"))))) AND (NOT (q."Proposal_details_ID" = 0)));


--ALTER TABLE public.orphan_questions_of_proposal_details_view OWNER TO wwwolamy;

--
-- Name: proposal_details_id_seq; Type: SEQUENCE; Schema: public; Owner: wwwolamy
--

CREATE SEQUENCE public.proposal_details_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--ALTER TABLE public.proposal_details_id_seq OWNER TO wwwolamy;

--
-- Name: proposal_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: wwwolamy
--

ALTER SEQUENCE public.proposal_details_id_seq OWNED BY public."Proposal_details"."ID";


--
-- Name: proposal_questions_view_2; Type: VIEW; Schema: public; Owner: wwwolamy
--

CREATE VIEW public.proposal_questions_view_2 AS
SELECT
    NULL::integer AS "Users-ID",
    NULL::character varying(255) AS "Role",
    NULL::character varying(100) AS "First-Name",
    NULL::character varying(100) AS "Last-Name",
    NULL::character varying(12) AS "Phone",
    NULL::character varying(255) AS "Email",
    NULL::integer AS "Proposal-details-ID",
    NULL::character varying(255) AS "Title",
    NULL::jsonb AS "Question-classes",
    NULL::jsonb AS "Questions-and-Answers",
    NULL::timestamp without time zone AS created_date_time,
    NULL::timestamp without time zone AS updated_date_time;


--ALTER TABLE public.proposal_questions_view_2 OWNER TO wwwolamy;

--
-- Name: question_classes_id_seq; Type: SEQUENCE; Schema: public; Owner: wwwolamy
--

CREATE SEQUENCE public.question_classes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--ALTER TABLE public.question_classes_id_seq OWNER TO wwwolamy;

--
-- Name: question_classes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: wwwolamy
--

ALTER SEQUENCE public.question_classes_id_seq OWNED BY public."Question_classes"."ID";


--
-- Name: questions_id_seq; Type: SEQUENCE; Schema: public; Owner: wwwolamy
--

CREATE SEQUENCE public.questions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--ALTER TABLE public.questions_id_seq OWNER TO wwwolamy;

--
-- Name: questions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: wwwolamy
--

ALTER SEQUENCE public.questions_id_seq OWNED BY public."Questions"."ID";


--
-- Name: sched_Calendar; Type: TABLE; Schema: public; Owner: wwwolamy
--

CREATE TABLE public."sched_Calendar" (
    "ID" integer NOT NULL,
    "Venue_ID" integer DEFAULT 0 NOT NULL,
    day integer,
    start time without time zone,
    "end" time without time zone,
    created_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    "Proposal_details_ID" integer DEFAULT 0
);


--ALTER TABLE public."sched_Calendar" OWNER TO wwwolamy;

--
-- Name: COLUMN "sched_Calendar"."ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."sched_Calendar"."ID" IS 'PK';


--
-- Name: COLUMN "sched_Calendar"."Venue_ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."sched_Calendar"."Venue_ID" IS 'FK';


--
-- Name: COLUMN "sched_Calendar".day; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."sched_Calendar".day IS 'an integer, 1 - 4';


--
-- Name: COLUMN "sched_Calendar"."Proposal_details_ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."sched_Calendar"."Proposal_details_ID" IS 'FK';


--
-- Name: sched_Calendar_ID_seq; Type: SEQUENCE; Schema: public; Owner: wwwolamy
--

CREATE SEQUENCE public."sched_Calendar_ID_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--ALTER TABLE public."sched_Calendar_ID_seq" OWNER TO wwwolamy;

--
-- Name: sched_Calendar_ID_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: wwwolamy
--

ALTER SEQUENCE public."sched_Calendar_ID_seq" OWNED BY public."sched_Calendar"."ID";


--
-- Name: sched_Calendar_Venue_ID_seq; Type: SEQUENCE; Schema: public; Owner: wwwolamy
--

CREATE SEQUENCE public."sched_Calendar_Venue_ID_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--ALTER TABLE public."sched_Calendar_Venue_ID_seq" OWNER TO wwwolamy;

--
-- Name: sched_Calendar_Venue_ID_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: wwwolamy
--

ALTER SEQUENCE public."sched_Calendar_Venue_ID_seq" OWNED BY public."sched_Calendar"."Venue_ID";


--
-- Name: sched_events_id_seq; Type: SEQUENCE; Schema: public; Owner: wwwolamy
--

CREATE SEQUENCE public.sched_events_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--ALTER TABLE public.sched_events_id_seq OWNER TO wwwolamy;

--
-- Name: sched_Events; Type: TABLE; Schema: public; Owner: wwwolamy
--

CREATE TABLE public."sched_Events" (
    "ID" integer DEFAULT nextval('public.sched_events_id_seq'::regclass) NOT NULL,
    "Proposal_details_ID" integer DEFAULT 0,
    duration integer DEFAULT 0,
    "maxOccurences" integer DEFAULT 0,
    "preferredTimes" json DEFAULT '[]'::json,
    created_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--ALTER TABLE public."sched_Events" OWNER TO wwwolamy;

--
-- Name: COLUMN "sched_Events"."ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."sched_Events"."ID" IS 'PK';


--
-- Name: COLUMN "sched_Events"."Proposal_details_ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."sched_Events"."Proposal_details_ID" IS 'FK';


--
-- Name: COLUMN "sched_Events".duration; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."sched_Events".duration IS 'minutes';


--
-- Name: sched_events_members_id_seq; Type: SEQUENCE; Schema: public; Owner: wwwolamy
--

CREATE SEQUENCE public.sched_events_members_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--ALTER TABLE public.sched_events_members_id_seq OWNER TO wwwolamy;

--
-- Name: sched_Events_Members; Type: TABLE; Schema: public; Owner: wwwolamy
--

CREATE TABLE public."sched_Events_Members" (
    "ID" integer DEFAULT nextval('public.sched_events_members_id_seq'::regclass) NOT NULL,
    "sched_Events_ID" integer DEFAULT 0 NOT NULL,
    "sched_Members_ID" integer DEFAULT 0 NOT NULL
);


--ALTER TABLE public."sched_Events_Members" OWNER TO wwwolamy;

--
-- Name: COLUMN "sched_Events_Members"."ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."sched_Events_Members"."ID" IS 'PK';


--
-- Name: COLUMN "sched_Events_Members"."sched_Events_ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."sched_Events_Members"."sched_Events_ID" IS 'FK';


--
-- Name: COLUMN "sched_Events_Members"."sched_Members_ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."sched_Events_Members"."sched_Members_ID" IS 'FK';


--
-- Name: sched_events_resources_id_seq; Type: SEQUENCE; Schema: public; Owner: wwwolamy
--

CREATE SEQUENCE public.sched_events_resources_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--ALTER TABLE public.sched_events_resources_id_seq OWNER TO wwwolamy;

--
-- Name: sched_Events_Resources; Type: TABLE; Schema: public; Owner: wwwolamy
--

CREATE TABLE public."sched_Events_Resources" (
    "ID" integer DEFAULT nextval('public.sched_events_resources_id_seq'::regclass) NOT NULL,
    "sched_Events_ID" integer DEFAULT 0 NOT NULL,
    "sched_Resources_ID" integer DEFAULT 0 NOT NULL
);


--ALTER TABLE public."sched_Events_Resources" OWNER TO wwwolamy;

--
-- Name: COLUMN "sched_Events_Resources"."ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."sched_Events_Resources"."ID" IS 'PK';


--
-- Name: COLUMN "sched_Events_Resources"."sched_Events_ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."sched_Events_Resources"."sched_Events_ID" IS 'FK';


--
-- Name: COLUMN "sched_Events_Resources"."sched_Resources_ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."sched_Events_Resources"."sched_Resources_ID" IS 'FK';


--
-- Name: sched_members_id_seq; Type: SEQUENCE; Schema: public; Owner: wwwolamy
--

CREATE SEQUENCE public.sched_members_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--ALTER TABLE public.sched_members_id_seq OWNER TO wwwolamy;

--
-- Name: sched_Members; Type: TABLE; Schema: public; Owner: wwwolamy
--

CREATE TABLE public."sched_Members" (
    "ID" integer DEFAULT nextval('public.sched_members_id_seq'::regclass) NOT NULL,
    "home_Proposal_details_ID" integer,
    name character varying(100),
    "isShareable" boolean,
    created_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--ALTER TABLE public."sched_Members" OWNER TO wwwolamy;

--
-- Name: COLUMN "sched_Members"."ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."sched_Members"."ID" IS 'PK';


--
-- Name: COLUMN "sched_Members"."home_Proposal_details_ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."sched_Members"."home_Proposal_details_ID" IS 'FK';


--
-- Name: sched_resources_id_seq; Type: SEQUENCE; Schema: public; Owner: wwwolamy
--

CREATE SEQUENCE public.sched_resources_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--ALTER TABLE public.sched_resources_id_seq OWNER TO wwwolamy;

--
-- Name: sched_Resources; Type: TABLE; Schema: public; Owner: wwwolamy
--

CREATE TABLE public."sched_Resources" (
    "ID" integer DEFAULT nextval('public.sched_resources_id_seq'::regclass) NOT NULL,
    "home_Proposal_details_ID" integer,
    name character varying(100),
    "isShareable" boolean,
    created_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_date_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--ALTER TABLE public."sched_Resources" OWNER TO wwwolamy;

--
-- Name: COLUMN "sched_Resources"."ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."sched_Resources"."ID" IS 'PK';


--
-- Name: COLUMN "sched_Resources"."home_Proposal_details_ID"; Type: COMMENT; Schema: public; Owner: wwwolamy
--

COMMENT ON COLUMN public."sched_Resources"."home_Proposal_details_ID" IS 'FK';


--
-- Name: screenfield_validation_id_seq; Type: SEQUENCE; Schema: public; Owner: wwwolamy
--

CREATE SEQUENCE public.screenfield_validation_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--ALTER TABLE public.screenfield_validation_id_seq OWNER TO wwwolamy;

--
-- Name: screenfield_validation_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: wwwolamy
--

ALTER SEQUENCE public.screenfield_validation_id_seq OWNED BY public."Screenfield_validation"."ID";


--
-- Name: Genres ID; Type: DEFAULT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."Genres" ALTER COLUMN "ID" SET DEFAULT nextval('public.genres_id_seq'::regclass);


--
-- Name: Proposal_details ID; Type: DEFAULT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."Proposal_details" ALTER COLUMN "ID" SET DEFAULT nextval('public.proposal_details_id_seq'::regclass);


--
-- Name: Question_classes ID; Type: DEFAULT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."Question_classes" ALTER COLUMN "ID" SET DEFAULT nextval('public.question_classes_id_seq'::regclass);


--
-- Name: Questions ID; Type: DEFAULT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."Questions" ALTER COLUMN "ID" SET DEFAULT nextval('public.questions_id_seq'::regclass);


--
-- Name: Screenfield_validation ID; Type: DEFAULT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."Screenfield_validation" ALTER COLUMN "ID" SET DEFAULT nextval('public.screenfield_validation_id_seq'::regclass);


--
-- Name: sched_Calendar ID; Type: DEFAULT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."sched_Calendar" ALTER COLUMN "ID" SET DEFAULT nextval('public."sched_Calendar_ID_seq"'::regclass);


--
-- Data for Name: Genres; Type: TABLE DATA; Schema: public; Owner: wwwolamy
--

INSERT INTO public."Genres" VALUES (8, 'Dance--Ballet', '2025-03-08 20:31:38.759414', '2025-05-02 14:13:52.487862');
INSERT INTO public."Genres" VALUES (3, 'Literature--Mystery', '2025-03-08 20:17:53.488345', '2025-05-02 14:13:52.487862');
INSERT INTO public."Genres" VALUES (5, 'Music--Folk', '2025-03-08 20:31:38.759414', '2025-05-02 14:13:52.487862');
INSERT INTO public."Genres" VALUES (7, 'Dance--Break-dancing', '2025-03-08 20:31:38.759414', '2025-04-26 14:44:21.127413');
INSERT INTO public."Genres" VALUES (9, 'Dance--Square-dancing', '2025-03-08 20:31:38.759414', '2025-04-26 14:44:21.127413');
INSERT INTO public."Genres" VALUES (1, 'Literature--Romance', '2025-03-08 20:17:53.488345', '2025-04-26 14:44:21.127413');
INSERT INTO public."Genres" VALUES (2, 'Literature--SciFi', '2025-03-08 20:17:53.488345', '2025-04-26 14:44:21.127413');
INSERT INTO public."Genres" VALUES (4, 'Music--Classical', '2025-03-08 20:31:38.759414', '2025-04-26 14:44:21.127413');
INSERT INTO public."Genres" VALUES (6, 'Music--Jazz', '2025-03-08 20:31:38.759414', '2025-04-26 14:44:21.127413');
INSERT INTO public."Genres" VALUES (10, 'Performance--Avant-Garde', '2025-03-08 20:31:38.759414', '2025-04-26 14:44:21.127413');
INSERT INTO public."Genres" VALUES (12, 'Performance--Historical', '2025-03-08 20:31:38.759414', '2025-04-26 14:44:21.127413');
INSERT INTO public."Genres" VALUES (11, 'Performance--Improv', '2025-03-08 20:31:38.759414', '2025-04-26 14:44:21.127413');
INSERT INTO public."Genres" VALUES (16, 'Meme Culture', '2025-04-26 14:44:12.257391', '2025-04-26 14:44:21.127413');
INSERT INTO public."Genres" VALUES (20, 'Music--HipHop', '2025-11-17 19:58:47.41672', '2025-11-17 19:58:47.41672');


--
-- Data for Name: Proposal_details; Type: TABLE DATA; Schema: public; Owner: wwwolamy
--

INSERT INTO public."Proposal_details" VALUES (300, NULL, 'Lawn Day', NULL, '2026-01-25 06:36:12.101363', '2026-01-25 06:36:12.101363', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (315, 195, '3 days of duration cruelty listening healing and transformation through immersion in total art', NULL, '2026-02-01 09:02:54.193792', '2026-02-01 09:02:54.193792', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (306, 188, 'Something Clowny or Shakespeare(working title)', NULL, '2026-01-28 08:41:39.700185', '2026-01-28 08:41:39.700185', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (317, 199, 'West Side Public Radio', NULL, '2026-02-03 16:59:43.331074', '2026-02-03 16:59:43.331074', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (303, 184, 'Solo percussion performance with audience participation featuring a guest painter during intermission', NULL, '2026-01-27 09:13:22.997132', '2026-01-27 09:13:22.997132', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (232, 127, 'Dada', NULL, '2025-12-03 17:48:33.079298', '2025-12-15 22:56:50.542021', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (296, 177, 'unxtructured time', NULL, '2026-01-19 18:11:37.869672', '2026-01-19 18:11:37.869672', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (68, 78, 'I Shot McKinley', NULL, '2025-06-04 16:49:44.058177', '2026-01-28 16:48:01', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 8, "name": "put me Outdoors"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (314, 194, 'DIY Art Gallery', NULL, '2026-01-31 18:33:59.718514', '2026-02-01 11:11:22', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (277, 160, 'aaa', NULL, '2025-12-19 19:47:01.707506', '2025-12-19 19:47:01.707506', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (292, 173, 'Giant bubbles', NULL, '2026-01-19 17:04:15.07272', '2026-01-28 16:53:04', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (17, 52, 'Song about my Farm', NULL, '2025-04-26 14:20:03.160915', '2025-04-26 14:36:15.968525', '[{"ID": "9", "name": "(Include in ALL)"}, {"ID": "8", "name": "Street Performance"}, {"ID": "2", "name": "Music"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (310, 192, 'Trifecta on Trinity', NULL, '2026-01-28 19:17:39.585495', '2026-01-28 19:17:39.585495', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (290, 87, 'Untitled', NULL, '2026-01-18 12:57:35.303158', '2026-01-31 21:46:42', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}, {"ID": 9, "name": "General Questions"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (69, 78, 'Negative Clowns', NULL, '2025-06-04 16:54:44.634491', '2026-02-01 11:46:56', '[{"ID": 9, "name": "General Questions"}, {"ID": 1, "name": "Is a Live Performance (audience present)"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (299, 180, 'The DJ Vinyl Jerk presents “Gonzo Electro”', NULL, '2026-01-20 13:01:29.627174', '2026-01-20 13:01:29.627174', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (75, 89, 'Memetic Expression', NULL, '2025-06-05 19:12:16.548679', '2025-06-07 03:41:44.580575', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 6, "name": "Dance"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (66, 82, 'Butts', NULL, '2025-06-04 16:39:23.722346', '2025-07-16 17:19:12.890697', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 6, "name": "Dance"}, {"ID": 4, "name": "Literature"}, {"ID": 17, "name": "Live Performance"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (71, 83, 'Dance of the Mushroom Forest', NULL, '2025-06-04 17:16:43.685455', '2025-06-04 17:17:10.352793', '[{"ID": 21, "name": "Winstallation"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (61, 76, 'Something wonderful with large costumes and movement', NULL, '2025-05-21 17:47:42.966644', '2025-06-07 03:38:04.538321', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 6, "name": "Dance"}, {"ID": 17, "name": "Live Performance"}, {"ID": 2, "name": "Music"}, {"ID": 1, "name": "Performance"}, {"ID": 8, "name": "Street Performance"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (73, 85, 'Muppet jenny stands on her bead and sings the entirety of hamlet', NULL, '2025-06-04 17:30:40.284094', '2025-06-04 17:31:57.341679', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 2, "name": "Music"}, {"ID": 8, "name": "Street Performance"}, {"ID": 3, "name": "Theatre"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (304, 185, 'The Time Crisis of 2041', NULL, '2026-01-27 09:57:23.42861', '2026-01-28 02:38:12', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (266, 129, 'Languid movement', NULL, '2025-12-17 16:53:12.541365', '2026-01-28 02:41:54', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 6, "name": "incorporates Movement Arts"}, {"ID": 8, "name": "put me Outdoors"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (82, 78, 'refringe', NULL, '2025-06-08 12:33:53.588965', '2025-06-08 12:35:49.46596', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 6, "name": "Dance"}, {"ID": 5, "name": "Film"}, {"ID": 4, "name": "Literature"}, {"ID": 17, "name": "Live Performance"}, {"ID": 2, "name": "Music"}, {"ID": 1, "name": "Performance"}, {"ID": 8, "name": "Street Performance"}, {"ID": 3, "name": "Theatre"}, {"ID": 26, "name": "Venue"}, {"ID": 7, "name": "Virtual"}, {"ID": 21, "name": "Winstallation"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (307, 189, 'Thank You For Letting Us Create', NULL, '2026-01-28 14:52:47.333394', '2026-02-01 10:59:56', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}, {"ID": 9, "name": "General Questions"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (76, 90, 'Joe &amp;amp; Bob', NULL, '2025-06-05 20:18:08.877656', '2025-06-07 03:40:18.741521', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 1, "name": "Performance"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (293, 174, 'unstructured zone', NULL, '2026-01-19 17:29:41.023136', '2026-01-19 17:29:41.023136', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (316, 87, 'FIRD Friday', NULL, '2026-02-01 11:45:48.591976', '2026-02-01 11:45:48.591976', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (70, 78, 'Secrets Secrets', NULL, '2025-06-04 17:11:52.42595', '2025-06-07 03:37:08.161646', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 2, "name": "Music"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (64, 80, 'Clowns', NULL, '2025-06-04 16:36:28.666905', '2026-02-01 11:46:44', '[{"ID": 9, "name": "General Questions"}, {"ID": 3, "name": "Incorporates Theater"}]', 27);
INSERT INTO public."Proposal_details" VALUES (291, 172, 'Artlantis', NULL, '2026-01-18 15:31:56.485534', '2026-01-18 15:31:56.485534', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (110, 76, 'TangleLAB', NULL, '2025-08-23 13:47:28.616726', '2025-08-23 13:57:41.057856', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 28, "name": "Collaboration"}, {"ID": 17, "name": "Live Performance"}, {"ID": 1, "name": "Performance"}, {"ID": 30, "name": "Visual Art"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (269, 134, 'Play guitar and lyre', NULL, '2025-12-17 17:04:24.768624', '2025-12-17 17:05:25.458462', '[{"ID": 2, "name": "incorporates Music"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (230, 125, 'Fuckity fuck', NULL, '2025-12-03 17:38:08.194889', '2026-02-01 11:48:33', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (74, 86, '*Sigh* I&#039;m muppet Jenny&#039;s sister :(', NULL, '2025-06-04 17:43:36.358101', '2025-11-18 22:23:33.655305', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 5, "name": "Film"}, {"ID": 3, "name": "Theatre"}, {"ID": 26, "name": "Venue"}, {"ID": 7, "name": "Virtual"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (85, 95, 'Rhythmic Mind-Body Trance', NULL, '2025-06-18 18:29:47.2047', '2025-11-19 17:47:45.161561', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 17, "name": "Live Performance"}, {"ID": 6, "name": "Movement Arts"}, {"ID": 2, "name": "Music"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (113, 106, '“Words and Pictures” by Chris McGee w/ music by Haunted Gypsy', NULL, '2025-10-16 18:33:15.017955', '2025-11-06 06:44:33.863335', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 28, "name": "Collaboration"}, {"ID": 4, "name": "Literature"}, {"ID": 17, "name": "Live Performance"}, {"ID": 2, "name": "Music"}, {"ID": 1, "name": "Performance"}, {"ID": 30, "name": "Visual Art"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (308, 190, 'Artist', NULL, '2026-01-28 15:12:21.731181', '2026-01-29 14:58:22', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (264, 129, NULL, NULL, '2025-12-17 16:52:41.41048', '2025-12-17 16:52:41.41048', '[]', NULL);
INSERT INTO public."Proposal_details" VALUES (305, 186, 'Performance art', NULL, '2026-01-27 16:43:31.206246', '2026-01-27 17:10:39', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (313, 108, 'Lawn Day', NULL, '2026-01-29 18:43:45.136193', '2026-02-01 00:03:13', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}, {"ID": 9, "name": "General Questions"}, {"ID": 33, "name": "Incorporates Workshop/Educational"}, {"ID": 1, "name": "Is a Live Performance (audience present)"}, {"ID": 26, "name": "Put Me Indoors"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (228, 124, 'Whistling', NULL, '2025-12-03 16:19:17.42975', '2026-01-28 16:51:58', '[{"ID": 9, "name": "(Include in ALL)"}, {"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (298, 179, 'Tba', NULL, '2026-01-19 18:32:47.2599', '2026-01-19 18:32:47.2599', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (309, 191, 'La Marimba', NULL, '2026-01-28 15:17:31.190445', '2026-01-28 15:17:31.190445', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (240, 131, 'Busking', NULL, '2025-12-07 17:31:02.297437', '2026-02-02 13:17:20', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}, {"ID": 9, "name": "General Questions"}, {"ID": 5, "name": "Incorporates Film"}, {"ID": 4, "name": "Incorporates Literary Arts"}, {"ID": 6, "name": "Incorporates Movement Arts"}, {"ID": 2, "name": "Incorporates Music"}, {"ID": 3, "name": "Incorporates Theater"}, {"ID": 30, "name": "Incorporates Visual Art"}, {"ID": 33, "name": "Incorporates Workshop/Educational"}, {"ID": 1, "name": "Is a Live Performance (audience present)"}, {"ID": 29, "name": "Is an Installation (prepared in advance)"}, {"ID": 28, "name": "Open to Collaboration/Interaction"}, {"ID": 26, "name": "Put Me Indoors"}, {"ID": 7, "name": "Put Me Online"}, {"ID": 8, "name": "Put Me Outdoors"}, {"ID": 31, "name": "Shared Agreements"}, {"ID": 34, "name": "Want to participate as a Venue"}, {"ID": 35, "name": "Want to participate as a Volunteer"}]', 27);
INSERT INTO public."Proposal_details" VALUES (302, 183, 'Refringe Allentown Year 0', NULL, '2026-01-25 19:39:48.791827', '2026-01-31 23:59:55', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}, {"ID": 9, "name": "General Questions"}, {"ID": 5, "name": "Incorporates Film"}, {"ID": 4, "name": "Incorporates Literary Arts"}, {"ID": 6, "name": "Incorporates Movement Arts"}, {"ID": 2, "name": "Incorporates Music"}, {"ID": 3, "name": "Incorporates Theater"}, {"ID": 30, "name": "Incorporates Visual Art"}, {"ID": 33, "name": "Incorporates Workshop/Educational"}, {"ID": 1, "name": "Is a Live Performance (audience present)"}, {"ID": 29, "name": "Is an Installation (prepared in advance)"}, {"ID": 28, "name": "Open to Collaboration/Interaction"}, {"ID": 26, "name": "Put Me Indoors"}, {"ID": 7, "name": "Put Me Online"}, {"ID": 8, "name": "Put Me Outdoors"}, {"ID": 31, "name": "Shared Agreements"}, {"ID": 34, "name": "Want to participate as a Venue"}, {"ID": 35, "name": "Want to participate as a Volunteer"}]', NULL);
INSERT INTO public."Proposal_details" VALUES (268, 153, 'Busk', NULL, '2025-12-17 17:02:21.603939', '2026-02-01 00:04:08', '[{"ID": 32, "name": "A Quick Easy No Login Proposal"}, {"ID": 9, "name": "General Questions"}, {"ID": 5, "name": "Incorporates Film"}, {"ID": 4, "name": "Incorporates Literary Arts"}, {"ID": 6, "name": "Incorporates Movement Arts"}, {"ID": 2, "name": "Incorporates Music"}, {"ID": 3, "name": "Incorporates Theater"}, {"ID": 30, "name": "Incorporates Visual Art"}, {"ID": 33, "name": "Incorporates Workshop/Educational"}, {"ID": 1, "name": "Is a Live Performance (audience present)"}, {"ID": 29, "name": "Is an Installation (prepared in advance)"}, {"ID": 28, "name": "Open to Collaboration/Interaction"}, {"ID": 26, "name": "Put Me Indoors"}, {"ID": 7, "name": "Put Me Online"}, {"ID": 8, "name": "Put Me Outdoors"}, {"ID": 31, "name": "Shared Agreements"}, {"ID": 34, "name": "Want to participate as a Venue"}, {"ID": 35, "name": "Want to participate as a Volunteer"}]', NULL);


--
-- Data for Name: Question_classes; Type: TABLE DATA; Schema: public; Owner: wwwolamy
--

INSERT INTO public."Question_classes" VALUES (32, 'A Quick Easy No Login Proposal', '2025-11-23 13:02:19.629054', '2025-12-01 14:42:16.47431');
INSERT INTO public."Question_classes" VALUES (5, 'Incorporates Film', '2025-01-26 11:08:05.09777', '2026-01-31 04:19:38.142871');
INSERT INTO public."Question_classes" VALUES (9, 'General Questions', '2025-03-10 22:54:10.681861', '2026-01-31 04:19:38.142871');
INSERT INTO public."Question_classes" VALUES (4, 'Incorporates Literary Arts', '2025-01-26 11:08:05.09777', '2026-01-31 04:19:38.142871');
INSERT INTO public."Question_classes" VALUES (6, 'Incorporates Movement Arts', '2025-01-26 11:08:05.09777', '2026-01-31 04:19:38.142871');
INSERT INTO public."Question_classes" VALUES (2, 'Incorporates Music', '2025-01-26 11:08:05.09777', '2026-01-31 04:19:38.142871');
INSERT INTO public."Question_classes" VALUES (3, 'Incorporates Theater', '2025-01-26 11:08:05.09777', '2026-01-31 04:19:38.142871');
INSERT INTO public."Question_classes" VALUES (30, 'Incorporates Visual Art', '2025-08-13 19:08:27.810866', '2026-01-31 04:19:38.142871');
INSERT INTO public."Question_classes" VALUES (33, 'Incorporates Workshop/Educational', '2025-12-01 13:54:17.605966', '2026-01-31 04:19:38.142871');
INSERT INTO public."Question_classes" VALUES (1, 'Is a Live Performance (audience present)', '2025-01-26 11:08:05.09777', '2026-01-31 04:19:38.142871');
INSERT INTO public."Question_classes" VALUES (29, 'Is an Installation (prepared in advance)', '2025-06-23 18:13:18.803747', '2026-01-31 04:19:38.142871');
INSERT INTO public."Question_classes" VALUES (28, 'Open to Collaboration/Interaction', '2025-06-23 18:12:24.42946', '2026-01-31 04:19:38.142871');
INSERT INTO public."Question_classes" VALUES (31, 'Shared Agreements', '2025-11-19 17:40:22.874425', '2026-01-31 04:19:38.142871');
INSERT INTO public."Question_classes" VALUES (34, 'Want to participate as a Venue', '2026-01-21 19:27:12.807189', '2026-01-31 04:19:38.142871');
INSERT INTO public."Question_classes" VALUES (35, 'Want to participate as a Volunteer', '2026-01-21 19:27:47.465761', '2026-01-31 04:19:38.142871');
INSERT INTO public."Question_classes" VALUES (26, 'Put Me Indoors', '2025-05-04 11:04:25.830385', '2026-01-31 20:17:23.344982');
INSERT INTO public."Question_classes" VALUES (7, 'Put Me Online', '2025-01-26 11:08:05.09777', '2026-01-31 20:17:23.344982');
INSERT INTO public."Question_classes" VALUES (8, 'Put Me Outdoors', '2025-01-26 11:08:05.09777', '2026-01-31 20:17:23.344982');


--
-- Data for Name: Questions; Type: TABLE DATA; Schema: public; Owner: wwwolamy
--

INSERT INTO public."Questions" VALUES (473, 9, 304, '[{"answer": "", "question": "*Integer field:: How many members are involved in this proposal?"}, {"answer": "", "question": "*List all names and roles of all core participants (this helps us avoid scheduling conflicts)"}, {"answer": "", "question": "URL field:: Do you have a preferred social media profile or handle to be tagged as a participant in our own promotion efforts leading up to or during the event dates? Enter them here. (note: any Refringe promotion must be considered as *amplifying*, not replacing, your own independent promotion, and is not guaranteed.)"}, {"answer": "", "question": "URL field:: Copy and paste the URL of your preferred social media account or personal homepage, to share with the public in event listings or other publishable materials."}, {"answer": "", "question": "URL field:: Do you have an image link? Enter it here."}, {"answer": "", "question": "URL field:: Do you have a video link with examples of your work? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event contain adult content?"}, {"answer": "", "question": "*Yes / No / Maybe field:: Are all participants 21+?"}, {"answer": "", "question": "*Provide a description of your event that only for organizers - be detailed (surprises are for audiences, not organizers)"}, {"answer": "", "question": "*Up to 125 chars field:: Provide a short description of your proposal, fit for print."}, {"answer": "", "question": "*Provide a description of your event for the web. This may be as long as you&#039;d like."}, {"answer": "", "question": "*What does ReFringe mean to you?"}, {"answer": "", "question": "Does your work involve AI ? If so, how? (There are no disqualifying answers. This question is for organizational purposes ONLY.)"}, {"answer": "", "question": "Miscellaneous?"}, {"answer": "", "question": "List of all known or potential other proposals that you or other members are or may be involved in, whether as proposers or as core members of. (This helps us avoid unintentional double bookings)"}]', NULL, '2026-01-28 02:38:12.829838', '2026-01-28 02:38:12.829838');
INSERT INTO public."Questions" VALUES (152, 27, 0, '[]', NULL, '2025-06-23 18:10:23.985693', '2025-06-23 18:10:23.985693');
INSERT INTO public."Questions" VALUES (566, 32, 315, '[{"answer": "Thaddeus", "question": "*First Name"}, {"answer": "Cochrane", "question": "*Last Name"}, {"answer": "519-496-2754", "question": "*Phone Number field::Phone"}, {"answer": "thaddeus@claypotrecords.com", "question": "*Email field::Email"}, {"answer": "14201", "question": "*Zip field::ZIP Code"}, {"answer": "3 days of duration cruelty listening healing and transformation through immersion in total art", "question": "*Proposal Title"}, {"answer": "Music, theatre, literary, improv, visual arts, movement", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "experimental contemporary mixed media, theatre of cruelty/deep listening, art that does and does not mean", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "we’ll open up the house and create a layered installation. It begins on the sidewalk with sound and light projected down from the balcony and porch, something in the living room, something in the stairs, and a final secluded space in the loft. A continuous cycle of immersive theatre, poetry, music, and movement/visuals that you can dip in and out of over the days or endure for its duration. Specifics tbd, but something dramatic, large scale, and utterly transformative. Total art. I’ll try and bring as many people as I can to run the cycle and I’ll create a substantial part of the work myself but would like to involve others if they can dig it", "question": "*Proposal Description"}, {"answer": "Dave has heard my piece called “Blau” and has it in his instagram dms I think I can’t easily link it here but I can provide some stuff if you want to see more specifics ", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "hard to say at this stage", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "we probably have everything we need but I’ll let you know", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "our house, sound and lighting gear", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "our house and sidewalk and maybe the sidewalk on the other side, maybe the wall of that garage", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Yes", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "Experimental theatre, new music, poetry, lights, installation works, but no bullshit I am going to curate this shit", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "i’ll make sure I’m available, need to call out of work but that should be possible, yana will be available all day every day", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-02-01 09:02:54.194893', '2026-02-01 09:02:54.194893');
INSERT INTO public."Questions" VALUES (3, 3, 0, '[{"answer": "", "question": "Director(s)"}, {"answer": "", "question": "Cast"}, {"answer": "", "question": "Crew"}, {"answer": "", "question": "Production Co."}, {"answer": "", "question": "If you need amplification or stereo playback, please see above (performance) questions."}, {"answer": "", "question": "List pieces and sizes."}, {"answer": "", "question": "Do you have gallery space prearranged?"}, {"answer": "", "question": "Is there a live performance aspect?"}, {"answer": "", "question": "Is this an installation work?"}, {"answer": "", "question": "Describe your space needs, beyond wall space."}, {"answer": "", "question": "Would you be OK with other (non-visual) artists performing alongside your work?"}]', NULL, '2025-01-27 19:19:04.458252', '2026-01-08 17:06:53.798252');
INSERT INTO public."Questions" VALUES (97, 6, 61, '[{"answer": "No", "question": "Do you require a floor designed for dance?"}, {"answer": "Yes", "question": "Will you provide your own music playback?"}, {"answer": "Yes", "question": "Would you be willing to incorporate improvising musicians into a scheduled dance event?"}, {"answer": "potentially ", "question": "Can other performers share your gear?"}, {"answer": "No", "question": "Are you willing to perform with improvising musicians on a scheduled music event, even without a proper floor?"}, {"answer": "No", "question": "Does your act require video projection?"}, {"answer": "No", "question": "Does your act require lighting or staging?"}]', NULL, '2025-05-21 17:49:58.416814', '2025-05-21 18:00:03.769322');
INSERT INTO public."Questions" VALUES (113, 21, 71, '[{"answer": "a little space to set up an enchanted forest", "question": "What the DEVIL do you want???"}]', NULL, '2025-06-04 17:17:10.354703', '2025-06-04 17:17:39.28678');
INSERT INTO public."Questions" VALUES (123, 7, 74, '[{"answer": "", "question": "List pieces and sizes."}, {"answer": "", "question": "Do you have gallery space prearranged?"}, {"answer": "", "question": "Is there a live performance aspect?"}, {"answer": "", "question": "Is this an installation work?"}, {"answer": "", "question": "Describe your space needs, beyond wall space."}, {"answer": "", "question": "Would you be OK with other (non-visual) artists performing alongside your work?"}]', NULL, '2025-06-04 17:44:07.360682', '2025-06-04 17:44:07.360682');
INSERT INTO public."Questions" VALUES (180, 17, 110, '[{"answer": "its more of a group collaborative/ onteractive performance", "question": "Miscellaneous?"}]', NULL, '2025-08-23 13:49:32.595606', '2025-08-23 13:57:24.299078');
INSERT INTO public."Questions" VALUES (182, 30, 110, '[{"answer": "crochet, group performance, interactive art", "question": "What is your art medium?"}, {"answer": "several tbd", "question": "List pieces and sizes."}, {"answer": "no", "question": "Do you have gallery space prearranged?"}, {"answer": "yes", "question": "Is there a live performance aspect?"}, {"answer": "yes. after we are done we would install the work in the space", "question": "Is this an installation work?"}, {"answer": "chairs and space enough for people to crochet comfortably", "question": "Describe your space needs, beyond wall space."}, {"answer": "no", "question": "Would you be OK with other (non-visual) artists performing alongside your work?"}, {"answer": "", "question": "Miscellaneous?"}]', NULL, '2025-08-23 13:49:32.596704', '2025-08-23 13:57:24.299701');
INSERT INTO public."Questions" VALUES (217, 28, 113, '[{"answer": "Stories, songs, ideas expressed through artwork and music", "question": "Miscellaneous?"}]', NULL, '2025-10-17 11:39:20.607337', '2025-11-04 17:26:02.558501');
INSERT INTO public."Questions" VALUES (462, 34, 0, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-01-21 19:27:12.808477', '2026-01-31 23:30:35.007149');
INSERT INTO public."Questions" VALUES (265, 33, 0, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2025-12-01 13:54:17.607534', '2026-01-31 23:41:56.654652');
INSERT INTO public."Questions" VALUES (229, 17, 113, '[{"answer": "", "question": "Miscellaneous?"}]', NULL, '2025-11-04 16:46:49.021205', '2025-11-04 17:26:02.560275');
INSERT INTO public."Questions" VALUES (235, 17, 85, '[{"answer": "", "question": "Miscellaneous?"}]', NULL, '2025-11-19 17:30:03.054886', '2025-11-19 17:37:50.746479');
INSERT INTO public."Questions" VALUES (8, 8, 0, '[{"answer": "", "question": "Would you busk or perform, or both?"}, {"answer": "", "question": "Do you have experience busking?"}, {"answer": "", "question": "Do you have/can you obtain a busking license?"}, {"answer": "", "question": "Does your act roam?"}, {"answer": "", "question": "If necessary, do you have a mobile power source?"}, {"answer": "", "question": "Does your act rely on interaction or improv?"}, {"answer": "", "question": "Is your act appropriate for Kid-fringement and/or Bidwell Saturday Markets, or similar?"}, {"answer": "", "question": "If necessary, do you have a portable amp?"}]', NULL, '2025-01-27 19:19:04.458252', '2026-01-08 17:15:37.625593');
INSERT INTO public."Questions" VALUES (464, 32, 300, '[{"answer": "Jake", "question": "*First Name"}, {"answer": "Cassel", "question": "*Last Name"}, {"answer": "716-430-5800", "question": "*Phone Number field::Phone"}, {"answer": "jcassel@gmail.com", "question": "*Email field::Email"}, {"answer": "14221", "question": "*Zip field::ZIP Code"}, {"answer": "Lawn Day", "question": "*Proposal Title"}, {"answer": "Tactile Media, the idea of using cultural ephemeral artifacts supporting harm against themselves", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Mixed Media Temporary Land Sculpture Tactile Media Critical Theory Conceptual Art piece", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "A re-creation of the common yellow lawn spray sign on the plastic stick has been re-imagined in a way to subvert tone of understanding regarding a lawn spray culture which has been normalized despite clear harm. To all.", "question": "*Proposal Description"}, {"answer": "https://www.facebook.com/profile.php?id=100083742685517", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "None. Although maybe a well lit, alter-like one, as a main sign could be a bit of a spectacle.", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "Just geographical area with lawn.", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "0, unless some future embellishment occurs.", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "Geographic footprint of Refringe containing lawn to stick the signs in.", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "y", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "Any that are practical while supporting the effort of the concept in the piece.", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "likely around B-lo then", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-25 06:36:12.102453', '2026-01-25 06:36:12.102453');
INSERT INTO public."Questions" VALUES (304, 32, 232, '[{"answer": "David", "question": "*First Name"}, {"answer": "Ad", "question": "*Last Name"}, {"answer": "1234567", "question": "*Phone Number:: Phone"}, {"answer": "A@b.com", "question": "*Email:: Email"}, {"answer": "1234", "question": "*ZIP Code"}, {"answer": "Dada", "question": "*Project Title"}, {"answer": "Um, uh", "question": "*CommaSemiColonList:: Discipline(s)\nEx: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Violins", "question": "*CommaSemiColonList:: Genre(s) Within Your Discipline(s)\nEx: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines—such as movement/theatre or music/visual art—please list the genre and sub-genre for each discipline."}, {"answer": "Playing", "question": "*Proposal Description"}, {"answer": "", "question": "Work Sample Links (1–3)\nShare one to three links to examples of your work (if available)."}, {"answer": "Studf", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "Doing", "question": "*Equipment You Need Provided by Us\nEx: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "", "question": "Equipment You Could Provide\nExamples: sound gear, lighting gear, pop-up tent."}, {"answer": "Big", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Yes", "question": "*Yes / No / Maybe:: Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "Mostly", "question": "*AVAILABILITY (MAY 14–17)\nBe as specific as possible."}]', NULL, '2025-12-03 17:48:33.080241', '2025-12-15 22:57:23.687638');
INSERT INTO public."Questions" VALUES (39, 2, 17, '[{"answer": "Barn animals play acoustic, farmer uses loop pedals when near an outlet", "question": "I am/we are willing to play purely acoustic."}, {"answer": "Barns and deep south EDM nightclubs", "question": "What venues has your band played? Can you give a gig history?"}, {"answer": "A loud moo", "question": "How loud are you? (1-10)"}, {"answer": "Barn dance music", "question": "What groups or styles have you shared bills with?"}, {"answer": "Found farm sounds", "question": "Instrumentation."}, {"answer": "A mini pa", "question": "I/we requre a full PA."}, {"answer": "Here a mic, there a mic", "question": "When playing with a PA, how many vocal mics?"}, {"answer": "Yes for squaredance", "question": "I/we own a PA that others can use *and share* for shows?"}, {"answer": "Farm equipment ", "question": "I/we can you provide amps or other equipment to share?"}, {"answer": "My wife plays the spoons ", "question": "I/we can provide drums to share."}, {"answer": "Looped live farm samples ", "question": "I/we primarily perform as a DJ/laptopist/electronic instrumentalist."}, {"answer": "", "question": "I have DJ/mixing equipment I can share."}, {"answer": "The animals also do free jazz", "question": "I or my groups'' members would be interested in improvising in larger (scheduled) collaborations?"}]', NULL, '2025-04-26 14:21:26.108216', '2025-04-26 14:27:19.42811');
INSERT INTO public."Questions" VALUES (474, 32, 306, '[{"answer": "Monica", "question": "*First Name"}, {"answer": "Morrisey", "question": "*Last Name"}, {"answer": "716-424-5144", "question": "*Phone Number field::Phone"}, {"answer": "monica.morrisey0@gmail.com", "question": "*Email field::Email"}, {"answer": "14222", "question": "*Zip field::ZIP Code"}, {"answer": "Something Clowny or Shakespeare(working title)", "question": "*Proposal Title"}, {"answer": "Theater, improv, clown", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Something Clowny. Or Shakespeare.", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "I will do something pertaining to clowns or shakespeare", "question": "*Proposal Description"}, {"answer": "No.", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "Myself", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "None", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "I own a stage and fun clown stuff", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "All space and no space", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Maybe", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "People who like clowns or shakespeare. ", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "whatever man. ", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-28 08:41:39.701371', '2026-01-28 08:41:39.701371');
INSERT INTO public."Questions" VALUES (46, 22, 0, '[]', NULL, '2025-05-02 13:12:38.11119', '2025-05-02 13:12:38.11119');
INSERT INTO public."Questions" VALUES (138, 9, 82, '[{"answer": "", "question": "*Yes / No::Are you from out of town?"}, {"answer": "", "question": "*Integer::How many are you?"}, {"answer": "", "question": "*What are your names and roles?"}, {"answer": "", "question": "URL::Do you have a Facebook link? Enter it here."}, {"answer": "", "question": "URL::Do you have a personal website link? Enter it here."}, {"answer": "", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "", "question": "*Are all people participating in your event of age 21+?"}, {"answer": "", "question": "*Provide a description of your event that only you can review."}, {"answer": "", "question": "*Up to 125 chars::Provide a description of your event for the news."}, {"answer": "", "question": "*Provide a description of your event for the web."}, {"answer": "", "question": "*What ReFringe ideals will be demonstrated by your event?"}, {"answer": "", "question": "Notes (miscellaneous)?"}]', NULL, '2025-06-08 12:35:49.468405', '2025-06-08 12:35:49.468405');
INSERT INTO public."Questions" VALUES (44, 13, 0, '[]', NULL, '2025-04-26 23:37:44.196173', '2025-04-26 23:37:44.196173');
INSERT INTO public."Questions" VALUES (49, 25, 0, '[]', NULL, '2025-05-02 13:44:33.849044', '2025-05-02 13:44:33.849044');
INSERT INTO public."Questions" VALUES (179, 28, 110, '[{"answer": "Think of this as a finger crochet circle.", "question": "Miscellaneous?"}]', NULL, '2025-08-23 13:49:32.594895', '2025-08-23 13:57:24.298752');
INSERT INTO public."Questions" VALUES (5, 5, 0, '[{"answer": "", "question": "Live Screening?"}, {"answer": "", "question": "Installation?"}, {"answer": "", "question": "Genre"}, {"answer": "", "question": "Preview urls + instructions."}, {"answer": "", "question": "Can you provide a viewing space?"}, {"answer": "", "question": "Can you provide a projector and/or screen?"}, {"answer": "", "question": "Director(s)"}, {"answer": "", "question": "Cast"}, {"answer": "", "question": "Crew"}, {"answer": "", "question": "Production Co."}]', NULL, '2025-01-27 19:19:04.458252', '2026-01-08 17:06:11.66438');
INSERT INTO public."Questions" VALUES (139, 6, 82, '[{"answer": "", "question": "Do you require a floor designed for dance?"}, {"answer": "", "question": "Will you provide your own music playback?"}, {"answer": "", "question": "Would you be willing to incorporate improvising musicians into a scheduled dance event?"}, {"answer": "", "question": "Can other performers share your gear?"}, {"answer": "", "question": "Are you willing to perform with improvising musicians on a scheduled music event, even without a proper floor?"}, {"answer": "", "question": "Does your act require video projection?"}, {"answer": "", "question": "Does your act require lighting or staging?"}]', NULL, '2025-06-08 12:35:49.469121', '2025-06-08 12:35:49.469121');
INSERT INTO public."Questions" VALUES (463, 35, 0, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-01-21 19:27:47.466987', '2026-01-31 23:30:51.671435');
INSERT INTO public."Questions" VALUES (475, 32, 307, '[{"answer": "Jonl", "question": "*First Name"}, {"answer": "Gilliam", "question": "*Last Name"}, {"answer": "716-551-8040", "question": "*Phone Number field::Phone"}, {"answer": "lemacre@gmail.com", "question": "*Email field::Email"}, {"answer": "14222", "question": "*Zip field::ZIP Code"}, {"answer": "Thank You For Letting Us Create ", "question": "*Proposal Title"}, {"answer": "Visual Arts , Live Painting , Media , Canvas", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Visual Art / Live Paint ", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "Arts / Media ", "question": "*Proposal Description"}, {"answer": "Instagram users _weliveinchaos.nl @daysinaday @insannis.uni", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "We will be using canvases , wood pallets for paint , long table to place canvases , a tent to cover our space , easels to place canvases ", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "Wall Space And a Electric outlet/strip for charging devices ", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "Lighting , backdrop if needed, photographer if needed ", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "No requirements ", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Yes", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "Anything as long as it’s not defacing anyone", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "May 14th 15th 16th & 17th I am Available ", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-28 14:52:47.334777', '2026-01-28 14:52:47.334777');
INSERT INTO public."Questions" VALUES (40, 8, 17, '[{"answer": "Both", "question": "Would you busk or perform, or both?"}, {"answer": "Nope", "question": "Do you have experience busking?"}, {"answer": "Nope", "question": "Do you have/can you obtain a busking license?"}, {"answer": "Yes in the pasture", "question": "Does your act roam?"}, {"answer": "No", "question": "If necessary, do you have a mobile power source?"}, {"answer": "Yes", "question": "Does your act rely on interaction or improv?"}, {"answer": "Yes but need animal license", "question": "Is your act appropriate for Kid-fringement and/or Bidwell Saturday Markets, or similar?"}, {"answer": "No", "question": "If necessary, do you have a portable amp?"}]', NULL, '2025-04-26 14:27:29.419125', '2025-04-26 14:28:21.274283');
INSERT INTO public."Questions" VALUES (47, 23, 0, '[]', NULL, '2025-05-02 13:44:16.339557', '2025-05-02 13:44:16.339557');
INSERT INTO public."Questions" VALUES (45, 21, 0, '[{"answer": "", "question": "What the DEVIL do you want???"}]', NULL, '2025-04-26 23:40:53.628265', '2025-04-26 23:41:47.706535');
INSERT INTO public."Questions" VALUES (140, 5, 82, '[{"answer": "", "question": "Live Screening?"}, {"answer": "", "question": "Installation?"}, {"answer": "", "question": "Genre"}, {"answer": "", "question": "Preview urls + instructions."}, {"answer": "", "question": "Can you provide a viewing space?"}, {"answer": "", "question": "Can you provide a projector and/or screen?"}, {"answer": "", "question": "Director(s)"}, {"answer": "", "question": "Cast"}, {"answer": "", "question": "Crew"}, {"answer": "", "question": "Production Co."}]', NULL, '2025-06-08 12:35:49.46968', '2025-06-08 12:35:49.46968');
INSERT INTO public."Questions" VALUES (141, 4, 82, '[{"answer": "", "question": "*What themes and/or topics do you explore?"}, {"answer": "", "question": "Titles (and co-authorship, etc.) for works you would perform."}, {"answer": "", "question": "I do not wish to list titles, or it doesn''t apply to me."}, {"answer": "", "question": "Publisher/other affiliation."}]', NULL, '2025-06-08 12:35:49.470199', '2025-06-08 12:35:49.470199');
INSERT INTO public."Questions" VALUES (382, 6, 266, '[{"answer": "", "question": "Will you provide your own music playback?"}, {"answer": "", "question": "Would you be willing to incorporate improvising musicians into a scheduled dance event?"}, {"answer": "", "question": "Are you willing to perform with improvising musicians on a scheduled music event, even without a proper floor?"}, {"answer": "", "question": "What surfaces are you comfortable working on? Or not working on?"}, {"answer": "", "question": "Do you require a floor designed for dance?"}, {"answer": "", "question": "Can other performers share your gear?"}, {"answer": "", "question": "Does your act include video projection? (If so be mindful that you must bring your own equipment--unless otherwise prearranged.)"}, {"answer": "", "question": "Does your act require lighting or staging? (If so be mindful that the venue may not provide these.)"}, {"answer": "", "question": "Miscellaneous?"}]', NULL, '2025-12-17 16:54:02.941163', '2025-12-17 17:16:03.557476');
INSERT INTO public."Questions" VALUES (383, 8, 266, '[{"answer": "", "question": "Would you busk or perform, or both?"}, {"answer": "", "question": "Do you have experience busking?"}, {"answer": "", "question": "Do you have/can you obtain a busking license?"}, {"answer": "", "question": "Does your act roam?"}, {"answer": "", "question": "If necessary, do you have a mobile power source?"}, {"answer": "", "question": "Does your act rely on interaction or improv?"}, {"answer": "", "question": "Is your act appropriate for Kid-fringement and/or Bidwell Saturday Markets, or similar?"}, {"answer": "", "question": "If necessary, do you have a portable amp?"}, {"answer": "", "question": "Miscellaneous?"}]', NULL, '2025-12-17 16:54:02.941942', '2025-12-17 17:16:03.558436');
INSERT INTO public."Questions" VALUES (142, 2, 82, '[{"answer": "", "question": "I am/we are willing to play purely acoustic."}, {"answer": "", "question": "What venues has your band played? Can you give a gig history?"}, {"answer": "", "question": "How loud are you? (1-10)"}, {"answer": "", "question": "What groups or styles have you shared bills with?"}, {"answer": "", "question": "Instrumentation."}, {"answer": "", "question": "I/we requre a full PA."}, {"answer": "", "question": "When playing with a PA, how many vocal mics?"}, {"answer": "", "question": "I/we own a PA that others can use *and share* for shows?"}, {"answer": "", "question": "I/we can you provide amps or other equipment to share?"}, {"answer": "", "question": "I/we can provide drums to share."}, {"answer": "", "question": "I/we primarily perform as a DJ/laptopist/electronic instrumentalist."}, {"answer": "", "question": "I have DJ/mixing equipment I can share."}, {"answer": "", "question": "I or my groups'' members would be interested in improvising in larger (scheduled) collaborations?"}]', NULL, '2025-06-08 12:35:49.471011', '2025-06-08 12:35:49.471011');
INSERT INTO public."Questions" VALUES (153, 28, 0, '[{"answer": "", "question": "Delimited List field:: Theme Tag(s)--For Organizer Use Only."}]', NULL, '2025-06-23 18:12:24.430269', '2026-01-08 17:17:19.118722');
INSERT INTO public."Questions" VALUES (41, 9, 17, '[{"answer": "Yes", "question": "*Yes / No::Are you from out of town?"}, {"answer": "100", "question": "*Integer::How many are you?"}, {"answer": "I don''t name the animals. I''m old MacDonald and have a farm ", "question": "*What are your names and roles?"}, {"answer": "Www.farmbook.com", "question": "URL::Do you have a Facebook link? Enter it here."}, {"answer": "Justspiderwebs.Com", "question": "URL::Do you have a personal website link? Enter it here."}, {"answer": "", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "Salad, cheese", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "Yes", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "Yes", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "No they don''t live that long", "question": "*Are all people participating in your event of age 21+?"}, {"answer": "Song about having a farm and what Sounds the things on my farm make ", "question": "*Provide a description of your event that only you can review."}, {"answer": "I have a farm and it has animals and they make sounds here, there, and everywhere", "question": "*Up to 125 chars::Provide a description of your event for the news."}, {"answer": "I have a farm and it has animals and they make sounds here, there, and everywhere", "question": "*Provide a description of your event for the web."}, {"answer": "other people can make the same sounds ", "question": "*What ReFringe ideals will be demonstrated by your event?"}, {"answer": "Poop bags", "question": "Notes (miscellaneous)?"}, {"answer": "Whrkiddn", "question": "Sporzervationob"}, {"answer": " Coajsms wherhfkgo", "question": "Iterpig"}, {"answer": "Protototototoooo", "question": "Pigerit"}, {"answer": "Hefwnahenarhhx", "question": "Hassafaffa"}]', NULL, '2025-04-26 14:28:29.838992', '2025-06-07 22:59:05.91463');
INSERT INTO public."Questions" VALUES (48, 24, 0, '[]', NULL, '2025-05-02 13:44:27.449266', '2025-05-02 13:44:27.449266');
INSERT INTO public."Questions" VALUES (384, 32, 268, '[{"answer": "Avidd", "question": "*First Name"}, {"answer": "Damczyka", "question": "*Last Name"}, {"answer": "11245567890", "question": "*Phone Number field:: Phone"}, {"answer": "DGADAMCZYK@GMAIL.COM", "question": "*Email field:: Email"}, {"answer": "14221", "question": "*Zip field:: ZIP Code"}, {"answer": "Busk", "question": "*Proposal Title"}, {"answer": "Music", "question": "*Delimited List field:: Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Violin", "question": "*Delimited List field:: Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines—such as movement/theatre or music/visual art—please list the genre and sub-genre for each discipline."}, {"answer": "Violin", "question": "*Proposal Description"}, {"answer": "", "question": "Delimited List field:: Work Sample Links (1–3)\nShare one to three links to examples of your work (if available)."}, {"answer": "Violin", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "Violin", "question": "*Delimited List field:: Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "", "question": "Delimited List field:: Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "Yea big ", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Y", "question": "*Yes / No / Maybe field:: Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "Ok", "question": "*AVAILABILITY (MAY 14–17)\nBe as specific as possible."}]', NULL, '2025-12-17 17:02:21.604946', '2025-12-17 17:02:21.604946');
INSERT INTO public."Questions" VALUES (143, 1, 82, '[{"answer": "", "question": "Performance duration - minimum"}, {"answer": "", "question": "Performance duration - maximum"}, {"answer": "", "question": "Setup time."}, {"answer": "", "question": "Teardown time."}, {"answer": "", "question": "What is the minimum amount of space for your performance?"}, {"answer": "", "question": "The majority of our performance is original works."}, {"answer": "", "question": "We are interested in playing outside."}, {"answer": "", "question": "We have a venue prearranged."}, {"answer": "", "question": "Our act requires seating."}, {"answer": "", "question": "I am willing to perform in non-traditional settings."}, {"answer": "", "question": "I am willing to perform at opening/closing ceremonies."}, {"answer": "", "question": "We would like to project images."}, {"answer": "", "question": "I book or otherwise organize bills/shows (not just those involving my own group)."}, {"answer": "", "question": "I''d like to help run a show."}, {"answer": "", "question": "Are you open to artists in different modalities sharing your time/space?"}]', NULL, '2025-06-08 12:35:49.471619', '2025-06-08 12:35:49.471619');
INSERT INTO public."Questions" VALUES (144, 8, 82, '[{"answer": "", "question": "Would you busk or perform, or both?"}, {"answer": "", "question": "Do you have experience busking?"}, {"answer": "", "question": "Do you have/can you obtain a busking license?"}, {"answer": "", "question": "Does your act roam?"}, {"answer": "", "question": "If necessary, do you have a mobile power source?"}, {"answer": "", "question": "Does your act rely on interaction or improv?"}, {"answer": "", "question": "Is your act appropriate for Kid-fringement and/or Bidwell Saturday Markets, or similar?"}, {"answer": "", "question": "If necessary, do you have a portable amp?"}]', NULL, '2025-06-08 12:35:49.472177', '2025-06-08 12:35:49.472177');
INSERT INTO public."Questions" VALUES (145, 3, 82, '[{"answer": "", "question": "Director(s)"}, {"answer": "", "question": "Cast"}, {"answer": "", "question": "Crew"}, {"answer": "", "question": "Production Co."}, {"answer": "", "question": "If you need amplification or stereo playback, please see above (performance) questions."}, {"answer": "", "question": "List pieces and sizes."}, {"answer": "", "question": "Do you have gallery space prearranged?"}, {"answer": "", "question": "Is there a live performance aspect?"}, {"answer": "", "question": "Is this an installation work?"}, {"answer": "", "question": "Describe your space needs, beyond wall space."}, {"answer": "", "question": "Would you be OK with other (non-visual) artists performing alongside your work?"}]', NULL, '2025-06-08 12:35:49.472706', '2025-06-08 12:35:49.472706');
INSERT INTO public."Questions" VALUES (146, 26, 82, '[]', NULL, '2025-06-08 12:35:49.473218', '2025-06-08 12:35:49.473218');
INSERT INTO public."Questions" VALUES (476, 32, 308, '[{"answer": "Anthony", "question": "*First Name"}, {"answer": "Mott", "question": "*Last Name"}, {"answer": "716-748-1951", "question": "*Phone Number field::Phone"}, {"answer": "insannisproductions@gmail.com", "question": "*Email field::Email"}, {"answer": "14216", "question": "*Zip field::ZIP Code"}, {"answer": "Artist ", "question": "*Proposal Title"}, {"answer": "Visual Arts/Dance", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Live painting", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "Art and Creation for the community ", "question": "*Proposal Description"}, {"answer": "", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "Posca markers, acrylics ", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "Floor space, music and a good time", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "Enough for big statement artwork", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Yes", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "Good vibes and loves art is a good combo for me", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "Available for May 14th-17th", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-28 15:12:21.732292', '2026-01-28 15:12:21.732292');
INSERT INTO public."Questions" VALUES (147, 7, 82, '[{"answer": "", "question": "List pieces and sizes."}, {"answer": "", "question": "Do you have gallery space prearranged?"}, {"answer": "", "question": "Is there a live performance aspect?"}, {"answer": "", "question": "Is this an installation work?"}, {"answer": "", "question": "Describe your space needs, beyond wall space."}, {"answer": "", "question": "Would you be OK with other (non-visual) artists performing alongside your work?"}]', NULL, '2025-06-08 12:35:49.47375', '2025-06-08 12:35:49.47375');
INSERT INTO public."Questions" VALUES (148, 21, 82, '[{"answer": "", "question": "What the DEVIL do you want???"}]', NULL, '2025-06-08 12:35:49.474287', '2025-06-08 12:35:49.474287');
INSERT INTO public."Questions" VALUES (155, 9, 66, '[{"answer": "", "question": "*Yes / No::Are you from out of town?"}, {"answer": "", "question": "*Integer::How many are you?"}, {"answer": "", "question": "*What are your names and roles?"}, {"answer": "", "question": "URL::Do you have a Facebook link? Enter it here."}, {"answer": "", "question": "URL::Do you have a personal website link? Enter it here."}, {"answer": "", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "", "question": "*Are all people participating in your event of age 21+?"}, {"answer": "", "question": "*Provide a description of your event that only you can review."}, {"answer": "", "question": "*Up to 125 chars::Provide a description of your event for the news."}, {"answer": "", "question": "*Provide a description of your event for the web."}, {"answer": "", "question": "*What ReFringe ideals will be demonstrated by your event?"}, {"answer": "", "question": "Notes (miscellaneous)?"}]', NULL, '2025-07-16 17:19:12.892584', '2025-07-16 17:19:12.892584');
INSERT INTO public."Questions" VALUES (100, 8, 61, '[{"answer": "Perform", "question": "Would you busk or perform, or both?"}, {"answer": "Yes", "question": "Do you have experience busking?"}, {"answer": "Yes", "question": "Do you have/can you obtain a busking license?"}, {"answer": "Yes", "question": "Does your act roam?"}, {"answer": "Yes", "question": "If necessary, do you have a mobile power source?"}, {"answer": "Potentially ", "question": "Does your act rely on interaction or improv?"}, {"answer": "Yes", "question": "Is your act appropriate for Kid-fringement and/or Bidwell Saturday Markets, or similar?"}, {"answer": "Yes", "question": "If necessary, do you have a portable amp?"}]', NULL, '2025-05-21 17:49:58.419024', '2025-05-21 18:00:03.771569');
INSERT INTO public."Questions" VALUES (477, 32, 309, '[{"answer": "Tiffany ", "question": "*First Name"}, {"answer": "Nicely ", "question": "*Last Name"}, {"answer": "716-515-5997", "question": "*Phone Number field::Phone"}, {"answer": "Tiffnicely@aol.com", "question": "*Email field::Email"}, {"answer": "14213", "question": "*Zip field::ZIP Code"}, {"answer": "La Marimba", "question": "*Proposal Title"}, {"answer": "Music", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Traditional, folk", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "La Marimba is a trio presenting our original take on traditional music of Mexico and Central America. ", "question": "*Proposal Description"}, {"answer": "https://youtu.be/Ej_JQLDl2tE?si=RkJzzZJDBqiXSkcb\nhttps://youtu.be/yepHOI1pGOI?si=dzDCEOwpOD6OV8KN\nhttps://youtu.be/EnfZHpoYX4Q?si=80AlMC7o462rhjNk\n", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "One marimba (large xylophone) with two players sharing it, small drum set", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "N/A", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "N/A, just our instruments", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "10 x 20-foot space ", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Yes", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "Dancers, painters", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "Wide open as of now", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-28 15:17:31.191569', '2026-01-28 15:17:31.191569');
INSERT INTO public."Questions" VALUES (483, 32, 311, '[{"answer": "Thomas Kendall", "question": "*First Name"}, {"answer": "Pajaro Carpintero aaa", "question": "*Last Name"}, {"answer": "716-364-8650", "question": "*Phone Number field::Phone"}, {"answer": "lagartija.tc@gmail.com", "question": "*Email field::Email"}, {"answer": "14222", "question": "*Zip field::ZIP Code"}, {"answer": "Test After Login", "question": "*Proposal Title"}, {"answer": "111", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "222", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "333", "question": "*Proposal Description"}, {"answer": "444", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "555", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "666", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "777", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "888", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "y", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "9999", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "14", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-29 12:21:06.9188', '2026-01-31 01:27:28.704141');
INSERT INTO public."Questions" VALUES (385, 2, 269, '[{"answer": "Yes", "question": "I am/we are willing to play purely acoustic."}, {"answer": "many senior homes", "question": "What venues has your band played? Can you give a gig history?"}, {"answer": "not very", "question": "Integer (1 - 10)::How loud are you?"}, {"answer": "classical guitarists", "question": "What groups or styles have you shared bills with?"}, {"answer": "guitars and lyres", "question": "Instrumentation."}, {"answer": "No", "question": "I/we requre a full PA."}, {"answer": "I don’t know ", "question": "When playing with a PA, how many vocal mics?"}, {"answer": "no", "question": "I/we own a PA that others can use *and share* for shows?"}, {"answer": "ok", "question": "I/we can you provide amps or other equipment to share?"}, {"answer": "yes", "question": "I/we can provide drums to share."}, {"answer": "no", "question": "I/we primarily perform as a DJ/laptopist/electronic instrumentalist."}, {"answer": "no", "question": "I have DJ/mixing equipment I can share."}, {"answer": "yes", "question": "I or my groups'' members would be interested in improvising in larger (scheduled) collaborations?"}, {"answer": "I like to explain history of historical instruments before performing ", "question": "Miscellaneous?"}]', NULL, '2025-12-17 17:05:20.049197', '2025-12-17 17:10:24.31506');
INSERT INTO public."Questions" VALUES (6, 6, 0, '[{"answer": "", "question": "Will you provide your own music playback?"}, {"answer": "", "question": "Would you be willing to incorporate improvising musicians into a scheduled dance event?"}, {"answer": "", "question": "Are you willing to perform with improvising musicians on a scheduled music event, even without a proper floor?"}, {"answer": "", "question": "What surfaces are you comfortable working on? Or not working on?"}, {"answer": "", "question": "Do you require a floor designed for dance?"}, {"answer": "", "question": "Can other performers share your gear?"}, {"answer": "", "question": "Does your act include video projection? (If so be mindful that you must bring your own equipment--unless otherwise prearranged.)"}, {"answer": "", "question": "Does your act require lighting or staging? (If so be mindful that the venue may not provide these.)"}]', NULL, '2025-01-27 19:19:04.458252', '2026-01-08 17:06:32.433564');
INSERT INTO public."Questions" VALUES (2, 2, 0, '[{"answer": "", "question": "I am/we are willing to play purely acoustic."}, {"answer": "", "question": "What venues has your band played? Can you give a gig history?"}, {"answer": "", "question": "Integer (1 - 10) field:: How loud are you?"}, {"answer": "", "question": "What groups or styles have you shared bills with?"}, {"answer": "", "question": "Instrumentation."}, {"answer": "", "question": "I/we requre a full PA."}, {"answer": "", "question": "When playing with a PA, how many vocal mics?"}, {"answer": "", "question": "I/we own a PA that others can use *and share* for shows?"}, {"answer": "", "question": "I/we can you provide amps or other equipment to share?"}, {"answer": "", "question": "I/we can provide drums to share."}, {"answer": "", "question": "I/we primarily perform as a DJ/laptopist/electronic instrumentalist."}, {"answer": "", "question": "I have DJ/mixing equipment I can share."}, {"answer": "", "question": "I or my groups'' members would be interested in improvising in larger (scheduled) collaborations?"}]', NULL, '2025-01-27 19:19:04.458252', '2026-01-08 17:06:44.655094');
INSERT INTO public."Questions" VALUES (478, 9, 308, '[{"answer": "", "question": "*Integer field:: How many members are involved in this proposal?"}, {"answer": "", "question": "*List all names and roles of all core participants (this helps us avoid scheduling conflicts)"}, {"answer": "", "question": "URL field:: Do you have a preferred social media profile or handle to be tagged as a participant in our own promotion efforts leading up to or during the event dates? Enter them here. (note: any Refringe promotion must be considered as *amplifying*, not replacing, your own independent promotion, and is not guaranteed.)"}, {"answer": "", "question": "URL field:: Copy and paste the URL of your preferred social media account or personal homepage, to share with the public in event listings or other publishable materials."}, {"answer": "", "question": "URL field:: Do you have an image link? Enter it here."}, {"answer": "", "question": "URL field:: Do you have a video link with examples of your work? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event contain adult content?"}, {"answer": "", "question": "*Yes / No / Maybe field:: Are all participants 21+?"}, {"answer": "", "question": "*Provide a description of your event that only for organizers - be detailed (surprises are for audiences, not organizers)"}, {"answer": "", "question": "*Up to 125 chars field:: Provide a short description of your proposal, fit for print."}, {"answer": "", "question": "*Provide a description of your event for the web. This may be as long as you&#039;d like."}, {"answer": "", "question": "*What does ReFringe mean to you?"}, {"answer": "", "question": "Does your work involve AI ? If so, how? (There are no disqualifying answers. This question is for organizational purposes ONLY.)"}, {"answer": "", "question": "Miscellaneous?"}, {"answer": "", "question": "List of all known or potential other proposals that you or other members are or may be involved in, whether as proposers or as core members of. (This helps us avoid unintentional double bookings)"}]', NULL, '2026-01-28 16:46:35.456304', '2026-01-28 16:46:35.456304');
INSERT INTO public."Questions" VALUES (485, 32, 312, '[{"answer": "Thomas", "question": "*First Name"}, {"answer": "CaRpEnTeR", "question": "*Last Name"}, {"answer": "111-222-3333", "question": "*Phone Number field::Phone"}, {"answer": "lagartija.Tc@GmAiL.CoM", "question": "*Email field::Email"}, {"answer": "14222", "question": "*Zip field::ZIP Code"}, {"answer": "222", "question": "*Proposal Title"}, {"answer": "333", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "444", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "555", "question": "*Proposal Description"}, {"answer": "666", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "777", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "888", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "999", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "101010", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "n", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "11 11 11", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "14-17", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-29 12:47:56.683111', '2026-01-29 12:47:56.683111');
INSERT INTO public."Questions" VALUES (124, 6, 75, '[{"answer": "", "question": "Do you require a floor designed for dance?"}, {"answer": "", "question": "Will you provide your own music playback?"}, {"answer": "", "question": "Would you be willing to incorporate improvising musicians into a scheduled dance event?"}, {"answer": "", "question": "Can other performers share your gear?"}, {"answer": "", "question": "Are you willing to perform with improvising musicians on a scheduled music event, even without a proper floor?"}, {"answer": "", "question": "Does your act require video projection?"}, {"answer": "", "question": "Does your act require lighting or staging?"}]', NULL, '2025-06-05 19:13:55.333905', '2025-06-05 19:13:55.333905');
INSERT INTO public."Questions" VALUES (108, 6, 66, '[{"answer": "no", "question": "Do you require a floor designed for dance?"}, {"answer": "yes", "question": "Will you provide your own music playback?"}, {"answer": "sure thing", "question": "Would you be willing to incorporate improvising musicians into a scheduled dance event?"}, {"answer": "no gear just butts. I mean yes :)", "question": "Can other performers share your gear?"}, {"answer": "yes", "question": "Are you willing to perform with improvising musicians on a scheduled music event, even without a proper floor?"}, {"answer": "no", "question": "Does your act require video projection?"}, {"answer": "no", "question": "Does your act require lighting or staging?"}]', NULL, '2025-06-04 16:40:41.399332', '2025-06-04 16:43:09.156809');
INSERT INTO public."Questions" VALUES (109, 4, 66, '[{"answer": "Butts and asses", "question": "*What themes and/or topics do you explore?"}, {"answer": "Original Butt Poem", "question": "Titles (and co-authorship, etc.) for works you would perform."}, {"answer": "", "question": "I do not wish to list titles, or it doesn''t apply to me."}, {"answer": "", "question": "Publisher/other affiliation."}]', NULL, '2025-06-04 16:40:41.400096', '2025-06-04 16:43:09.158276');
INSERT INTO public."Questions" VALUES (111, 1, 69, '[{"answer": "", "question": "Performance duration - minimum"}, {"answer": "", "question": "Performance duration - maximum"}, {"answer": "", "question": "Setup time."}, {"answer": "", "question": "Teardown time."}, {"answer": "", "question": "What is the minimum amount of space for your performance?"}, {"answer": "", "question": "The majority of our performance is original works."}, {"answer": "", "question": "We are interested in playing outside."}, {"answer": "", "question": "We have a venue prearranged."}, {"answer": "", "question": "Our act requires seating."}, {"answer": "", "question": "I am willing to perform in non-traditional settings."}, {"answer": "", "question": "I am willing to perform at opening/closing ceremonies."}, {"answer": "", "question": "We would like to project images."}, {"answer": "", "question": "I book or otherwise organize bills/shows (not just those involving my own group)."}, {"answer": "", "question": "I''d like to help run a show."}, {"answer": "", "question": "Are you open to artists in different modalities sharing your time/space?"}]', NULL, '2025-06-04 16:56:04.824391', '2025-06-04 16:56:04.824391');
INSERT INTO public."Questions" VALUES (479, 9, 292, '[{"answer": "", "question": "*Integer field:: How many members are involved in this proposal?"}, {"answer": "", "question": "*List all names and roles of all core participants (this helps us avoid scheduling conflicts)"}, {"answer": "", "question": "URL field:: Do you have a preferred social media profile or handle to be tagged as a participant in our own promotion efforts leading up to or during the event dates? Enter them here. (note: any Refringe promotion must be considered as *amplifying*, not replacing, your own independent promotion, and is not guaranteed.)"}, {"answer": "", "question": "URL field:: Copy and paste the URL of your preferred social media account or personal homepage, to share with the public in event listings or other publishable materials."}, {"answer": "", "question": "URL field:: Do you have an image link? Enter it here."}, {"answer": "", "question": "URL field:: Do you have a video link with examples of your work? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event contain adult content?"}, {"answer": "", "question": "*Yes / No / Maybe field:: Are all participants 21+?"}, {"answer": "", "question": "*Provide a description of your event that only for organizers - be detailed (surprises are for audiences, not organizers)"}, {"answer": "", "question": "*Up to 125 chars field:: Provide a short description of your proposal, fit for print."}, {"answer": "", "question": "*Provide a description of your event for the web. This may be as long as you&#039;d like."}, {"answer": "", "question": "*What does ReFringe mean to you?"}, {"answer": "", "question": "Does your work involve AI ? If so, how? (There are no disqualifying answers. This question is for organizational purposes ONLY.)"}, {"answer": "", "question": "Miscellaneous?"}, {"answer": "", "question": "List of all known or potential other proposals that you or other members are or may be involved in, whether as proposers or as core members of. (This helps us avoid unintentional double bookings)"}]', NULL, '2026-01-28 16:53:04.904376', '2026-01-28 16:53:04.904376');
INSERT INTO public."Questions" VALUES (381, 9, 266, '[{"answer": "Blah", "question": "*Integer::How many members are involved in this proposal?"}, {"answer": "Blah", "question": "*List all names and roles of all core participants (this helps us avoid scheduling conflicts)"}, {"answer": "", "question": "URL::Do you have a *personal* website link? Enter it here."}, {"answer": "", "question": "URL::Copy and paste the URL of your preferred social media account, to share with the public."}, {"answer": "", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "Blah", "question": "*Yes / No::Are all participants 21+?"}, {"answer": "Vlah", "question": "*Provide a description of your event that only for organizers - be detailed (surprises are for audiences, not organizers)"}, {"answer": "Blah", "question": "*Up to 125 chars::Provide a short description of your proposal, fit for print."}, {"answer": "Blah", "question": "*Provide a description of your event for the web. This may be as long as you''d like."}, {"answer": "Boo", "question": "*What does ReFringe mean to you?"}, {"answer": "", "question": "Does your work involve AI ? If so, how? (There are no disqualifying answers. This question is for organizational purposes ONLY.)"}, {"answer": "", "question": "Miscellaneous?"}]', NULL, '2025-12-17 16:54:02.940162', '2025-12-17 17:16:03.555389');
INSERT INTO public."Questions" VALUES (486, 9, 312, '[{"answer": "", "question": "*Integer field:: How many members are involved in this proposal?"}, {"answer": "", "question": "*List all names and roles of all core participants (this helps us avoid scheduling conflicts)"}, {"answer": "", "question": "URL field:: Do you have a preferred social media profile or handle to be tagged as a participant in our own promotion efforts leading up to or during the event dates? Enter them here. (note: any Refringe promotion must be considered as *amplifying*, not replacing, your own independent promotion, and is not guaranteed.)"}, {"answer": "", "question": "URL field:: Copy and paste the URL of your preferred social media account or personal homepage, to share with the public in event listings or other publishable materials."}, {"answer": "", "question": "URL field:: Do you have an image link? Enter it here."}, {"answer": "", "question": "URL field:: Do you have a video link with examples of your work? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event contain adult content?"}, {"answer": "", "question": "*Yes / No / Maybe field:: Are all participants 21+?"}, {"answer": "", "question": "*Provide a description of your event that only for organizers - be detailed (surprises are for audiences, not organizers)"}, {"answer": "", "question": "*Up to 125 chars field:: Provide a short description of your proposal, fit for print."}, {"answer": "", "question": "*Provide a description of your event for the web. This may be as long as you&#039;d like."}, {"answer": "", "question": "*What does ReFringe mean to you?"}, {"answer": "", "question": "Does your work involve AI ? If so, how? (There are no disqualifying answers. This question is for organizational purposes ONLY.)"}, {"answer": "", "question": "Miscellaneous?"}, {"answer": "", "question": "List of all known or potential other proposals that you or other members are or may be involved in, whether as proposers or as core members of. (This helps us avoid unintentional double bookings)"}]', NULL, '2026-01-29 12:52:25.945726', '2026-01-29 12:52:25.945726');
INSERT INTO public."Questions" VALUES (567, 32, 316, '[{"answer": "Curt", "question": "*First Name"}, {"answer": "Rotterdam", "question": "*Last Name"}, {"answer": "716-602-2464", "question": "*Phone Number field::Phone"}, {"answer": "steelcrazybooking@gmail.com", "question": "*Email field::Email"}, {"answer": "14201", "question": "*Zip field::ZIP Code"}, {"answer": "FIRD Friday", "question": "*Proposal Title"}, {"answer": "Thank, this is a group show idea", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Tba", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "A backyard party on Friday, with pop up gallery, activities, and entertainment ", "question": "*Proposal Description"}, {"answer": "Tba", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "Tba", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "tba", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "tba", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "We have the backyard art 62 college st.", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Yes", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "15th. ", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-02-01 11:45:48.59318', '2026-02-01 11:45:48.59318');
INSERT INTO public."Questions" VALUES (348, 8, 240, '[{"answer": "", "question": "Would you busk or perform, or both?"}, {"answer": "", "question": "Do you have experience busking?"}, {"answer": "", "question": "Do you have/can you obtain a busking license?"}, {"answer": "", "question": "Does your act roam?"}, {"answer": "", "question": "If necessary, do you have a mobile power source?"}, {"answer": "", "question": "Does your act rely on interaction or improv?"}, {"answer": "", "question": "Is your act appropriate for Kid-fringement and/or Bidwell Saturday Markets, or similar?"}, {"answer": "", "question": "If necessary, do you have a portable amp?"}, {"answer": "", "question": "Miscellaneous?"}]', NULL, '2025-12-07 17:32:51.594819', '2025-12-07 17:32:51.594819');
INSERT INTO public."Questions" VALUES (99, 1, 61, '[{"answer": "20 minutes", "question": "Performance duration - minimum"}, {"answer": "1 hour", "question": "Performance duration - maximum"}, {"answer": "20 minutes ", "question": "Setup time."}, {"answer": "20 minutes", "question": "Teardown time."}, {"answer": "Sidewalks", "question": "What is the minimum amount of space for your performance?"}, {"answer": "Yes", "question": "The majority of our performance is original works."}, {"answer": "Yes", "question": "We are interested in playing outside."}, {"answer": "No", "question": "We have a venue prearranged."}, {"answer": "No", "question": "Our act requires seating."}, {"answer": "Yes", "question": "I am willing to perform in non-traditional settings."}, {"answer": "Yes", "question": "I am willing to perform at opening/closing ceremonies."}, {"answer": "No", "question": "We would like to project images."}, {"answer": "Potentially ", "question": "I book or otherwise organize bills/shows (not just those involving my own group)."}, {"answer": "Potentially ", "question": "I''d like to help run a show."}, {"answer": "Potwntially", "question": "Are you open to artists in different modalities sharing your time/space?"}]', NULL, '2025-05-21 17:49:58.418582', '2025-05-21 18:00:03.771237');
INSERT INTO public."Questions" VALUES (480, 32, 310, '[{"answer": "Rhonda Lee", "question": "*First Name"}, {"answer": "73feathers", "question": "*Last Name"}, {"answer": "(716)6031448", "question": "*Phone Number field::Phone"}, {"answer": "73feathers@gmail.com", "question": "*Email field::Email"}, {"answer": "14201", "question": "*Zip field::ZIP Code"}, {"answer": "Trifecta on Trinity", "question": "*Proposal Title"}, {"answer": "Multi", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "N/A", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "A Trio of houses hosting all mediums and genres ", "question": "*Proposal Description"}, {"answer": "", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "N/a", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "N/A", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "N/A", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "N/A", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "N/A", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-28 19:17:39.586517', '2026-01-28 19:17:39.586517');
INSERT INTO public."Questions" VALUES (125, 1, 76, '[{"answer": "", "question": "Performance duration - minimum"}, {"answer": "", "question": "Performance duration - maximum"}, {"answer": "", "question": "Setup time."}, {"answer": "", "question": "Teardown time."}, {"answer": "", "question": "What is the minimum amount of space for your performance?"}, {"answer": "", "question": "The majority of our performance is original works."}, {"answer": "", "question": "We are interested in playing outside."}, {"answer": "", "question": "We have a venue prearranged."}, {"answer": "", "question": "Our act requires seating."}, {"answer": "", "question": "I am willing to perform in non-traditional settings."}, {"answer": "", "question": "I am willing to perform at opening/closing ceremonies."}, {"answer": "", "question": "We would like to project images."}, {"answer": "", "question": "I book or otherwise organize bills/shows (not just those involving my own group)."}, {"answer": "", "question": "I''d like to help run a show."}, {"answer": "", "question": "Are you open to artists in different modalities sharing your time/space?"}]', NULL, '2025-06-05 20:20:54.93113', '2025-06-05 20:20:54.93113');
INSERT INTO public."Questions" VALUES (110, 8, 68, '[{"answer": "both", "question": "Would you busk or perform, or both?"}, {"answer": "a little", "question": "Do you have experience busking?"}, {"answer": "I can obtain one", "question": "Do you have/can you obtain a busking license?"}, {"answer": "doesn''t have to but can", "question": "Does your act roam?"}, {"answer": "not necessary", "question": "If necessary, do you have a mobile power source?"}, {"answer": "both", "question": "Does your act rely on interaction or improv?"}, {"answer": "maybe", "question": "Is your act appropriate for Kid-fringement and/or Bidwell Saturday Markets, or similar?"}, {"answer": "no", "question": "If necessary, do you have a portable amp?"}]', NULL, '2025-06-04 16:51:58.642121', '2025-06-04 16:52:53.090915');
INSERT INTO public."Questions" VALUES (487, 9, 307, '[{"answer": "", "question": "*Integer field:: How many members are involved in this proposal?"}, {"answer": "", "question": "*List all names and roles of all core participants (this helps us avoid scheduling conflicts)"}, {"answer": "", "question": "URL field:: Do you have a preferred social media profile or handle to be tagged as a participant in our own promotion efforts leading up to or during the event dates? Enter them here. (note: any Refringe promotion must be considered as *amplifying*, not replacing, your own independent promotion, and is not guaranteed.)"}, {"answer": "", "question": "URL field:: Copy and paste the URL of your preferred social media account or personal homepage, to share with the public in event listings or other publishable materials."}, {"answer": "", "question": "URL field:: Do you have an image link? Enter it here."}, {"answer": "", "question": "URL field:: Do you have a video link with examples of your work? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event contain adult content?"}, {"answer": "", "question": "*Yes / No / Maybe field:: Are all participants 21+?"}, {"answer": "", "question": "*Provide a description of your event that only for organizers - be detailed (surprises are for audiences, not organizers)"}, {"answer": "", "question": "*Up to 125 chars field:: Provide a short description of your proposal, fit for print."}, {"answer": "", "question": "*Provide a description of your event for the web. This may be as long as you&#039;d like."}, {"answer": "", "question": "*What does ReFringe mean to you?"}, {"answer": "", "question": "Does your work involve AI ? If so, how? (There are no disqualifying answers. This question is for organizational purposes ONLY.)"}, {"answer": "", "question": "Miscellaneous?"}, {"answer": "", "question": "List of all known or potential other proposals that you or other members are or may be involved in, whether as proposers or as core members of. (This helps us avoid unintentional double bookings)"}]', NULL, '2026-01-29 14:57:49.854054', '2026-01-29 14:57:49.854054');
INSERT INTO public."Questions" VALUES (98, 2, 61, '[{"answer": "Yes", "question": "I am/we are willing to play purely acoustic."}, {"answer": "Biodance, wander and wonder, dances at muccc, dance limerick, Alfred university black box , ", "question": "What venues has your band played? Can you give a gig history?"}, {"answer": "", "question": "How loud are you? (1-10)"}, {"answer": "Electronic music, live experimental music,", "question": "What groups or styles have you shared bills with?"}, {"answer": "Modular synthesizers, percussion, body percussion, roving musicians", "question": "Instrumentation."}, {"answer": "No", "question": "I/we requre a full PA."}, {"answer": "None", "question": "When playing with a PA, how many vocal mics?"}, {"answer": "No", "question": "I/we own a PA that others can use *and share* for shows?"}, {"answer": "No", "question": "I/we can you provide amps or other equipment to share?"}, {"answer": "No", "question": "I/we can provide drums to share."}, {"answer": "No", "question": "I/we primarily perform as a DJ/laptopist/electronic instrumentalist."}, {"answer": "No", "question": "I have DJ/mixing equipment I can share."}, {"answer": "Potwntially", "question": "I or my groups'' members would be interested in improvising in larger (scheduled) collaborations?"}]', NULL, '2025-05-21 17:49:58.418105', '2025-05-21 18:00:03.770876');
INSERT INTO public."Questions" VALUES (116, 2, 73, '[{"answer": "", "question": "I am/we are willing to play purely acoustic."}, {"answer": "", "question": "What venues has your band played? Can you give a gig history?"}, {"answer": "", "question": "How loud are you? (1-10)"}, {"answer": "", "question": "What groups or styles have you shared bills with?"}, {"answer": "", "question": "Instrumentation."}, {"answer": "", "question": "I/we requre a full PA."}, {"answer": "", "question": "When playing with a PA, how many vocal mics?"}, {"answer": "", "question": "I/we own a PA that others can use *and share* for shows?"}, {"answer": "", "question": "I/we can you provide amps or other equipment to share?"}, {"answer": "", "question": "I/we can provide drums to share."}, {"answer": "", "question": "I/we primarily perform as a DJ/laptopist/electronic instrumentalist."}, {"answer": "", "question": "I have DJ/mixing equipment I can share."}, {"answer": "", "question": "I or my groups'' members would be interested in improvising in larger (scheduled) collaborations?"}]', NULL, '2025-06-04 17:31:57.344299', '2025-06-04 17:31:57.344299');
INSERT INTO public."Questions" VALUES (117, 8, 73, '[{"answer": "", "question": "Would you busk or perform, or both?"}, {"answer": "", "question": "Do you have experience busking?"}, {"answer": "", "question": "Do you have/can you obtain a busking license?"}, {"answer": "", "question": "Does your act roam?"}, {"answer": "", "question": "If necessary, do you have a mobile power source?"}, {"answer": "", "question": "Does your act rely on interaction or improv?"}, {"answer": "", "question": "Is your act appropriate for Kid-fringement and/or Bidwell Saturday Markets, or similar?"}, {"answer": "", "question": "If necessary, do you have a portable amp?"}]', NULL, '2025-06-04 17:31:57.344835', '2025-06-04 17:31:57.344835');
INSERT INTO public."Questions" VALUES (18, 9, 0, '[{"answer": "", "question": "*Integer field:: How many members are involved in this proposal?"}, {"answer": "", "question": "*List all names and roles of all core participants (this helps us avoid scheduling conflicts)"}, {"answer": "", "question": "URL field:: Do you have a preferred social media profile or handle to be tagged as a participant in our own promotion efforts leading up to or during the event dates? Enter them here. (note: any Refringe promotion must be considered as *amplifying*, not replacing, your own independent promotion, and is not guaranteed.)"}, {"answer": "", "question": "URL field:: Copy and paste the URL of your preferred social media account or personal homepage, to share with the public in event listings or other publishable materials."}, {"answer": "", "question": "URL field:: Do you have an image link? Enter it here."}, {"answer": "", "question": "URL field:: Do you have a video link with examples of your work? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event contain adult content?"}, {"answer": "", "question": "*Yes / No / Maybe field:: Are all participants 21+?"}, {"answer": "", "question": "*Provide a description of your event that only for organizers - be detailed (surprises are for audiences, not organizers)"}, {"answer": "", "question": "*Up to 125 chars field:: Provide a short description of your proposal, fit for print."}, {"answer": "", "question": "*Provide a description of your event for the web. This may be as long as you&#039;d like."}, {"answer": "", "question": "*What does ReFringe mean to you?"}, {"answer": "", "question": "Does your work involve AI ? If so, how? (There are no disqualifying answers. This question is for organizational purposes ONLY.)"}, {"answer": "", "question": "Miscellaneous?"}, {"answer": "", "question": "List of all known or potential other proposals that you or other members are or may be involved in, whether as proposers or as core members of. (This helps us avoid unintentional double bookings)"}]', NULL, '2025-03-10 23:07:16.016876', '2026-01-14 14:27:06.10079');
INSERT INTO public."Questions" VALUES (481, 9, 290, '[{"answer": "", "question": "*Integer field:: How many members are involved in this proposal?"}, {"answer": "", "question": "*List all names and roles of all core participants (this helps us avoid scheduling conflicts)"}, {"answer": "", "question": "URL field:: Do you have a preferred social media profile or handle to be tagged as a participant in our own promotion efforts leading up to or during the event dates? Enter them here. (note: any Refringe promotion must be considered as *amplifying*, not replacing, your own independent promotion, and is not guaranteed.)"}, {"answer": "", "question": "URL field:: Copy and paste the URL of your preferred social media account or personal homepage, to share with the public in event listings or other publishable materials."}, {"answer": "", "question": "URL field:: Do you have an image link? Enter it here."}, {"answer": "", "question": "URL field:: Do you have a video link with examples of your work? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event contain adult content?"}, {"answer": "", "question": "*Yes / No / Maybe field:: Are all participants 21+?"}, {"answer": "", "question": "*Provide a description of your event that only for organizers - be detailed (surprises are for audiences, not organizers)"}, {"answer": "", "question": "*Up to 125 chars field:: Provide a short description of your proposal, fit for print."}, {"answer": "", "question": "*Provide a description of your event for the web. This may be as long as you&#039;d like."}, {"answer": "", "question": "*What does ReFringe mean to you?"}, {"answer": "", "question": "Does your work involve AI ? If so, how? (There are no disqualifying answers. This question is for organizational purposes ONLY.)"}, {"answer": "", "question": "Miscellaneous?"}, {"answer": "", "question": "List of all known or potential other proposals that you or other members are or may be involved in, whether as proposers or as core members of. (This helps us avoid unintentional double bookings)"}]', NULL, '2026-01-28 19:28:06.817755', '2026-01-28 19:28:06.817755');
INSERT INTO public."Questions" VALUES (488, 32, 313, '[{"answer": "Jake", "question": "*First Name"}, {"answer": "Cassel", "question": "*Last Name"}, {"answer": "716-430-5800", "question": "*Phone Number field::Phone"}, {"answer": "jcassel@gmail.com", "question": "*Email field::Email"}, {"answer": "14221", "question": "*Zip field::ZIP Code"}, {"answer": "Lawn Day", "question": "*Proposal Title"}, {"answer": "Tactile Media, the idea of using cultural ephemeral artifacts supporting harm against themselves", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Mixed Media Temporary Land Sculpture Tactile Media Critical Theory Conceptual Art piece", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": " A re-creation of the common yellow lawn spray sign on the plastic stick has been re-imagined in a way to subvert tone of understanding regarding a lawn spray culture which has been normalized despite clear harm. To all.", "question": "*Proposal Description"}, {"answer": "https://www.facebook.com/profile.php?id=100083742685517", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "None. Although maybe a well lit, alter-like one, as a main sign could be a bit of a spectacle.", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "Just geographical area with lawn.", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "0, unless some future embellishment occurs.", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "Geographic footprint of Refringe containing lawn to stick the signs in.", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "y", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "Any that are practical while supporting the effort of the concept in the piece.", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "likely around B-lo then", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-29 18:43:45.137286', '2026-01-29 18:43:45.137286');
INSERT INTO public."Questions" VALUES (50, 26, 0, '[{"answer": "", "question": "Do you need a place to change clothes/costumes, i.e, a dressing room?"}]', NULL, '2025-05-04 11:04:25.831309', '2026-01-08 17:17:07.832783');
INSERT INTO public."Questions" VALUES (452, 32, 290, '[{"answer": "Curt", "question": "*First Name"}, {"answer": "Rotterdam", "question": "*Last Name"}, {"answer": "716-602-2464", "question": "*Phone Number field::Phone"}, {"answer": "steelcrazybooking@gmail.com", "question": "*Email field::Email"}, {"answer": "14201", "question": "*Zip field::ZIP Code"}, {"answer": "Untitled ", "question": "*Proposal Title"}, {"answer": "Visual art", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Mixed media ", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "I''ll probably contribute some of my art pieces to any Collective Gallery type setting also on the contribute to collaborative art installations", "question": "*Proposal Description"}, {"answer": "", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "I just have a couple of canvases that could be hung I also have some cigar boxes and pieces of wood that could be displayed for collaborative pieces", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "TBA", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "TBA", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "Tba\n", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Yes", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "Open to visual art collaboration,", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "All", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-18 12:57:35.30449', '2026-01-18 12:57:35.30449');
INSERT INTO public."Questions" VALUES (568, 32, 317, '[{"answer": "Hans", "question": "*First Name"}, {"answer": "Gruber", "question": "*Last Name"}, {"answer": "716-249-0858", "question": "*Phone Number field::Phone"}, {"answer": "wsprbuffalo@gmail.com", "question": "*Email field::Email"}, {"answer": "14213", "question": "*Zip field::ZIP Code"}, {"answer": "West Side Public Radio", "question": "*Proposal Title"}, {"answer": "Radio, Music, Interviews", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Microphone based communication skills", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "We could wither broadcast live events remote or if we can work it out with someone with rooftop access and 120V power we could relocate the station for better coverage of allentown for the duration of infringement. ", "question": "*Proposal Description"}, {"answer": "We are a terrestrial radio station that does not employ the use of streaming or recording digitally. Some shows are sent digitally but broadcasting generally goes into the ether. ", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "transmitter, antenna, microphone,  computer, tape deck, turntable etc ", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "120v power, at least a 20-30ft spot to mount antenna, dry weather tight space for gear if we need to go mobile. ", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "a mobile radio station.", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "small", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "yes", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "we would need some \"roadies\" to help if we wanted to record/broadcast more than a few things. ", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "depends but we can make stuff happen. ", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-02-03 16:59:43.331983', '2026-02-03 16:59:43.331983');
INSERT INTO public."Questions" VALUES (118, 3, 73, '[{"answer": "", "question": "Director(s)"}, {"answer": "", "question": "Cast"}, {"answer": "", "question": "Crew"}, {"answer": "", "question": "Production Co."}, {"answer": "", "question": "If you need amplification or stereo playback, please see above (performance) questions."}, {"answer": "", "question": "List pieces and sizes."}, {"answer": "", "question": "Do you have gallery space prearranged?"}, {"answer": "", "question": "Is there a live performance aspect?"}, {"answer": "", "question": "Is this an installation work?"}, {"answer": "", "question": "Describe your space needs, beyond wall space."}, {"answer": "", "question": "Would you be OK with other (non-visual) artists performing alongside your work?"}]', NULL, '2025-06-04 17:31:57.345353', '2025-06-04 17:31:57.345353');
INSERT INTO public."Questions" VALUES (154, 29, 0, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2025-06-23 18:13:18.80469', '2026-01-31 23:40:45.192695');
INSERT INTO public."Questions" VALUES (112, 2, 70, '[{"answer": "yes on the kazoo", "question": "I am/we are willing to play purely acoustic."}, {"answer": "none ever", "question": "What venues has your band played? Can you give a gig history?"}, {"answer": "5", "question": "How loud are you? (1-10)"}, {"answer": "no one", "question": "What groups or styles have you shared bills with?"}, {"answer": "Kazoo", "question": "Instrumentation."}, {"answer": "no", "question": "I/we requre a full PA."}, {"answer": "1", "question": "When playing with a PA, how many vocal mics?"}, {"answer": "no", "question": "I/we own a PA that others can use *and share* for shows?"}, {"answer": "no", "question": "I/we can you provide amps or other equipment to share?"}, {"answer": "no", "question": "I/we can provide drums to share."}, {"answer": "no", "question": "I/we primarily perform as a DJ/laptopist/electronic instrumentalist."}, {"answer": "no", "question": "I have DJ/mixing equipment I can share."}, {"answer": "yes", "question": "I or my groups'' members would be interested in improvising in larger (scheduled) collaborations?"}]', NULL, '2025-06-04 17:13:14.373363', '2025-06-04 17:14:13.407485');
INSERT INTO public."Questions" VALUES (490, 9, 313, '[{"answer": "", "question": "*Integer field:: How many members are involved in this proposal?"}, {"answer": "", "question": "*List all names and roles of all core participants (this helps us avoid scheduling conflicts)"}, {"answer": "", "question": "URL field:: Do you have a preferred social media profile or handle to be tagged as a participant in our own promotion efforts leading up to or during the event dates? Enter them here. (note: any Refringe promotion must be considered as *amplifying*, not replacing, your own independent promotion, and is not guaranteed.)"}, {"answer": "", "question": "URL field:: Copy and paste the URL of your preferred social media account or personal homepage, to share with the public in event listings or other publishable materials."}, {"answer": "", "question": "URL field:: Do you have an image link? Enter it here."}, {"answer": "", "question": "URL field:: Do you have a video link with examples of your work? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event contain adult content?"}, {"answer": "", "question": "*Yes / No / Maybe field:: Are all participants 21+?"}, {"answer": "", "question": "*Provide a description of your event that only for organizers - be detailed (surprises are for audiences, not organizers)"}, {"answer": "", "question": "*Up to 125 chars field:: Provide a short description of your proposal, fit for print."}, {"answer": "", "question": "*Provide a description of your event for the web. This may be as long as you&#039;d like."}, {"answer": "", "question": "*What does ReFringe mean to you?"}, {"answer": "", "question": "Does your work involve AI ? If so, how? (There are no disqualifying answers. This question is for organizational purposes ONLY.)"}, {"answer": "", "question": "Miscellaneous?"}, {"answer": "", "question": "List of all known or potential other proposals that you or other members are or may be involved in, whether as proposers or as core members of. (This helps us avoid unintentional double bookings)"}]', NULL, '2026-01-29 18:46:39.286073', '2026-01-29 18:46:39.286073');
INSERT INTO public."Questions" VALUES (120, 5, 74, '[{"answer": "", "question": "Live Screening?"}, {"answer": "", "question": "Installation?"}, {"answer": "", "question": "Genre"}, {"answer": "", "question": "Preview urls + instructions."}, {"answer": "", "question": "Can you provide a viewing space?"}, {"answer": "", "question": "Can you provide a projector and/or screen?"}, {"answer": "", "question": "Director(s)"}, {"answer": "", "question": "Cast"}, {"answer": "", "question": "Crew"}, {"answer": "", "question": "Production Co."}]', NULL, '2025-06-04 17:44:07.359335', '2025-06-04 17:44:07.359335');
INSERT INTO public."Questions" VALUES (121, 3, 74, '[{"answer": "", "question": "Director(s)"}, {"answer": "", "question": "Cast"}, {"answer": "", "question": "Crew"}, {"answer": "", "question": "Production Co."}, {"answer": "", "question": "If you need amplification or stereo playback, please see above (performance) questions."}, {"answer": "", "question": "List pieces and sizes."}, {"answer": "", "question": "Do you have gallery space prearranged?"}, {"answer": "", "question": "Is there a live performance aspect?"}, {"answer": "", "question": "Is this an installation work?"}, {"answer": "", "question": "Describe your space needs, beyond wall space."}, {"answer": "", "question": "Would you be OK with other (non-visual) artists performing alongside your work?"}]', NULL, '2025-06-04 17:44:07.359785', '2025-06-04 17:44:07.359785');
INSERT INTO public."Questions" VALUES (122, 26, 74, '[]', NULL, '2025-06-04 17:44:07.360232', '2025-06-04 17:44:07.360232');
INSERT INTO public."Questions" VALUES (4, 4, 0, '[{"answer": "", "question": "*What themes and/or topics do you explore?"}, {"answer": "", "question": "Titles (and co-authorship, etc.) for works you would perform."}, {"answer": "", "question": "I do not wish to list titles, or it doesn''t apply to me."}, {"answer": "", "question": "Publisher/other affiliation."}]', NULL, '2025-01-27 19:19:04.458252', '2026-01-08 17:06:19.755636');
INSERT INTO public."Questions" VALUES (453, 32, 291, '[{"answer": "Jackelyn ", "question": "*First Name"}, {"answer": "Kupinski", "question": "*Last Name"}, {"answer": "716-803-2258", "question": "*Phone Number field::Phone"}, {"answer": "8kupinski8@gmail.com", "question": "*Email field::Email"}, {"answer": "14225", "question": "*Zip field::ZIP Code"}, {"answer": "Artlantis", "question": "*Proposal Title"}, {"answer": "Art Installation interactive", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Interactive art with music ", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "Atlantis Interactive ocean display with lights bubbles and music. Possible interactive performances. ", "question": "*Proposal Description"}, {"answer": "", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "Wood and foam structures. Pop up tents. ", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "Large Space. May need external lighting for safety. ", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "Pop up tent. Wooden structure. Lighting is built into art. Music. Bubbles.  ", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "Lots of space. CURTS YARD WOULD BE ideal. Think large area ocean with photo opportunities for visitors. And spaces for performers. An immersion experience. ", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Yes", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "Added ocean themed works and performances. ", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "Open. ", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-18 15:31:56.486676', '2026-01-18 15:31:56.486676');
INSERT INTO public."Questions" VALUES (183, 9, 110, '[{"answer": "6", "question": "*Integer::How many members are involved in this proposal?"}, {"answer": "name, name, name, name, name, name, name", "question": "*List all names of artists."}, {"answer": "no", "question": "URL::Do you have a Facebook link? Enter it here."}, {"answer": "", "question": "URL::Do you have a personal website link? Enter it here."}, {"answer": "no", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "no", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "no", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "no", "question": "Any other links to show us?"}, {"answer": "yes", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "no", "question": "*Are all people participating in your event of age 21+?"}, {"answer": "It is a  live interactive performance that invites people to work together to create on large crochet object that entangles each individuals time/effort/ work/ into one stand alone 2d art piece.", "question": "*Provide a description of your event that only you can review."}, {"answer": "TangleLAB is an event open to people who want to learn to finger crochet together, and make a beautiful collective piece of art to share with the community.", "question": "*Up to 125 chars::Provide a description of your event for the news."}, {"answer": "TangleLAB is an event open to people who want to learn to finger crochet together, and make a beautiful collective piece of art to share with the community.", "question": "*Provide a description of your event for the web."}, {"answer": "Collective engagement, community, come as you are", "question": "*What ReFringe ideals will be demonstrated by your event?"}, {"answer": "no", "question": "Does your work involve AI ? If so, how? (There are no disqualifying answers. This question is for organizational purposes ONLY.)"}, {"answer": "", "question": "Miscellaneous?"}]', NULL, '2025-08-23 13:53:37.56617', '2025-08-23 13:57:24.297222');
INSERT INTO public."Questions" VALUES (181, 1, 110, '[{"answer": "2", "question": "Performance duration - minimum"}, {"answer": "3", "question": "Performance duration - maximum"}, {"answer": "30 minutes", "question": "Setup time."}, {"answer": "", "question": "Teardown time."}, {"answer": "a single room with seating", "question": "What is the minimum amount of space for your performance?"}, {"answer": "yes", "question": "The majority of our performance is original works."}, {"answer": "no", "question": "We are interested in playing outside."}, {"answer": "no", "question": "We have a venue prearranged."}, {"answer": "yes", "question": "Our act requires seating."}, {"answer": "yes", "question": "I am willing to perform in non-traditional settings."}, {"answer": "no", "question": "I am willing to perform at opening/closing ceremonies."}, {"answer": "no", "question": "We would like to project images."}, {"answer": "no", "question": "I book or otherwise organize bills/shows (not just those involving my own group)."}, {"answer": "maybe", "question": "I''d like to help run a show."}, {"answer": "maybe", "question": "Are you open to artists in different modalities sharing your time/space?"}, {"answer": "", "question": "Miscellaneous?"}]', NULL, '2025-08-23 13:49:32.596147', '2025-08-23 13:57:24.299395');
INSERT INTO public."Questions" VALUES (492, 1, 313, '[{"answer": "", "question": "Performance duration - minimum"}, {"answer": "", "question": "Performance duration - maximum"}, {"answer": "", "question": "Setup time."}, {"answer": "", "question": "Teardown time."}, {"answer": "", "question": "What is the minimum amount of space for your performance?"}, {"answer": "", "question": "The majority of our performance is original works."}, {"answer": "", "question": "We are interested in playing outside."}, {"answer": "", "question": "We have a venue prearranged."}, {"answer": "", "question": "Our act requires seating."}, {"answer": "", "question": "I am willing to perform in non-traditional settings."}, {"answer": "", "question": "I am willing to perform at opening/closing ceremonies."}, {"answer": "", "question": "We would like to project images."}, {"answer": "", "question": "I book or otherwise organize bills/shows (not just those involving my own group)."}, {"answer": "", "question": "I''d like to help run a show."}, {"answer": "", "question": "Are you open to artists in different modalities sharing your time/space?"}]', NULL, '2026-01-29 18:56:06.408038', '2026-01-29 18:56:06.408038');
INSERT INTO public."Questions" VALUES (454, 32, 292, '[{"answer": "Lauren", "question": "*First Name"}, {"answer": "Herman", "question": "*Last Name"}, {"answer": "716-771-7515", "question": "*Phone Number field::Phone"}, {"answer": "lo00311@gmail.com", "question": "*Email field::Email"}, {"answer": "14213", "question": "*Zip field::ZIP Code"}, {"answer": "Giant bubbles ", "question": "*Proposal Title"}, {"answer": "Other", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Making giant bubbles with multiple different wands", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "I''d like to bring multiple different wands, a bucket of bubbles for myself and a bucket (possible mini kiddie pool) with bubbles in it to make giant and all different size bubbles as I''m making different wands with MIA. I''d like to be stationed next to the clowns preferably in days parks as there is space and with my new setup and about to have back surgery, I need to have space and id like to show kids and adults how to make bubbles and possibly wands. ", "question": "*Proposal Description"}, {"answer": "", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "Multiple 5 gallon buckets, mini kiddie pool, bubble wands with a 9 foot fishing pole, multiple 3-4 foot wands. Pop up cones with rope to ensure i won''t get run into by a child", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "No equipment just space in days park and a close water source to get to with my trike or a wagon", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "A pop up tent", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "20''x20'' more if possible for others to make bubbles", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Y", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "especially with clowns and the underwater theme I''ve been told about by Jeannette ", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "May 15th from 2 on, may 16th and 17th from 11-12 on.", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-19 17:04:15.074119', '2026-01-19 17:04:15.074119');
INSERT INTO public."Questions" VALUES (133, 9, 69, '[{"answer": "", "question": "*Yes / No::Are you from out of town?"}, {"answer": "", "question": "*Integer::How many are you?"}, {"answer": "", "question": "*What are your names and roles?"}, {"answer": "", "question": "URL::Do you have a Facebook link? Enter it here."}, {"answer": "", "question": "URL::Do you have a personal website link? Enter it here."}, {"answer": "", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "", "question": "*Are all people participating in your event of age 21+?"}, {"answer": "", "question": "*Provide a description of your event that only you can review."}, {"answer": "", "question": "*Up to 125 chars::Provide a description of your event for the news."}, {"answer": "", "question": "*Provide a description of your event for the web."}, {"answer": "", "question": "*What ReFringe ideals will be demonstrated by your event?"}, {"answer": "", "question": "Notes (miscellaneous)?"}]', NULL, '2025-06-07 03:36:05.303376', '2025-06-07 22:59:05.91463');
INSERT INTO public."Questions" VALUES (493, 26, 313, '[{"answer": "", "question": "Do you need a place to change clothes/costumes, i.e, a dressing room?"}]', NULL, '2026-01-29 19:14:10.422315', '2026-01-29 19:14:10.422315');
INSERT INTO public."Questions" VALUES (1, 1, 0, '[{"answer": "", "question": "Performance duration - minimum"}, {"answer": "", "question": "Performance duration - maximum"}, {"answer": "", "question": "Setup time."}, {"answer": "", "question": "Teardown time."}, {"answer": "", "question": "What is the minimum amount of space for your performance?"}, {"answer": "", "question": "The majority of our performance is original works."}, {"answer": "", "question": "We are interested in playing outside."}, {"answer": "", "question": "We have a venue prearranged."}, {"answer": "", "question": "Our act requires seating."}, {"answer": "", "question": "I am willing to perform in non-traditional settings."}, {"answer": "", "question": "I am willing to perform at opening/closing ceremonies."}, {"answer": "", "question": "We would like to project images."}, {"answer": "", "question": "I book or otherwise organize bills/shows (not just those involving my own group)."}, {"answer": "", "question": "I''d like to help run a show."}, {"answer": "", "question": "Are you open to artists in different modalities sharing your time/space?"}]', NULL, '2025-01-27 19:19:04.458252', '2026-01-08 17:14:27.756923');
INSERT INTO public."Questions" VALUES (176, 17, 0, '[{"answer": "", "question": "Miscellaneous?"}]', NULL, '2025-08-13 18:29:29.972771', '2025-08-13 18:34:19.412305');
INSERT INTO public."Questions" VALUES (455, 32, 293, '[{"answer": "Steven", "question": "*First Name"}, {"answer": "Steven", "question": "*Last Name"}, {"answer": "504-208-6613", "question": "*Phone Number field::Phone"}, {"answer": "steve@invicta.org", "question": "*Email field::Email"}, {"answer": "14150", "question": "*Zip field::ZIP Code"}, {"answer": "unstructured zone", "question": "*Proposal Title"}, {"answer": "music visual arts, video, movement impove", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "fire spinning, dance not sure exactly what genres and sub-genres means but i''m new to this", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "festival attendees are invited to dip their feet and other body parts into trays of paint and apply the paint to a large roll of canvas that is laying on the ground.  think footprints everywhere!\n\nwe will have live house music being played by my buddy freeman spinning vinyl", "question": "*Proposal Description"}, {"answer": "bsteveb.com", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "paint, music, sound", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "electric, wifi, possibly self powered speaker and mixer\n\ndj should have everything except for a table to put mixer and turntables on", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "tbd", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "20x20 feet", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "yes", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "all collaboration", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "all day all night :)  each day we will display the previous days canvas;s ", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-19 17:29:41.024358', '2026-01-19 17:29:41.024358');
INSERT INTO public."Questions" VALUES (461, 32, 299, '[{"answer": "james", "question": "*First Name"}, {"answer": "Rust", "question": "*Last Name"}, {"answer": "716-803-5737", "question": "*Phone Number field::Phone"}, {"answer": "jedwardrust@gmail.com", "question": "*Email field::Email"}, {"answer": "14216-3102", "question": "*Zip field::ZIP Code"}, {"answer": "The DJ Vinyl Jerk presents “Gonzo Electro”", "question": "*Proposal Title"}, {"answer": "Mobile Dj cart perfect for infringing on the comfort of the proletariat. ", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Basically a mobile party cart. ", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "It’s still in development ", "question": "*Proposal Description"}, {"answer": "Should be self contained. Still figuring out power", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "Maybe I could add a guitar amp for an extra musician to join me", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "Na", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "Na", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "Na", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Yes", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "Na", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "Yes", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-20 13:01:29.628637', '2026-01-20 13:01:29.628637');
INSERT INTO public."Questions" VALUES (466, 32, 302, '[{"answer": "dAVID", "question": "*First Name"}, {"answer": "aDAMCZYK", "question": "*Last Name"}, {"answer": "7165786991", "question": "*Phone Number field::Phone"}, {"answer": "dGA8787@aol.com", "question": "*Email field::Email"}, {"answer": "14201", "question": "*Zip field::ZIP Code"}, {"answer": "Refringe Allentown Year 0", "question": "*Proposal Title"}, {"answer": "Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Multidisciplinary no matter how", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "Shows of shows", "question": "*Proposal Description"}, {"answer": "Hope so ", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "All available resources ", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "Could you plz not", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "Could you plz", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "How much space you got", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Y", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "The ones people won''t bail on", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "painfully available", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-25 19:39:48.792777', '2026-01-25 19:39:48.792777');
INSERT INTO public."Questions" VALUES (501, 4, 311, '[{"answer": "", "question": "*What themes and/or topics do you explore?"}, {"answer": "", "question": "Titles (and co-authorship, etc.) for works you would perform."}, {"answer": "", "question": "I do not wish to list titles, or it doesn''t apply to me."}, {"answer": "", "question": "Publisher/other affiliation."}]', NULL, '2026-01-31 04:55:52.351171', '2026-01-31 04:55:52.351171');
INSERT INTO public."Questions" VALUES (132, 9, 68, '[{"answer": "", "question": "*Yes / No::Are you from out of town?"}, {"answer": "", "question": "*Integer::How many are you?"}, {"answer": "", "question": "*What are your names and roles?"}, {"answer": "", "question": "URL::Do you have a Facebook link? Enter it here."}, {"answer": "", "question": "URL::Do you have a personal website link? Enter it here."}, {"answer": "", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "", "question": "*Are all people participating in your event of age 21+?"}, {"answer": "", "question": "*Provide a description of your event that only you can review."}, {"answer": "", "question": "*Up to 125 chars::Provide a description of your event for the news."}, {"answer": "", "question": "*Provide a description of your event for the web."}, {"answer": "", "question": "*What ReFringe ideals will be demonstrated by your event?"}, {"answer": "", "question": "Notes (miscellaneous)?"}]', NULL, '2025-06-07 03:35:02.530924', '2025-06-07 22:59:05.91463');
INSERT INTO public."Questions" VALUES (178, 30, 0, '[{"answer": "", "question": "What is your art medium?"}, {"answer": "", "question": "List pieces and sizes."}, {"answer": "", "question": "Do you have gallery space prearranged?"}, {"answer": "", "question": "Is there a live performance aspect?"}, {"answer": "", "question": "Is this an installation work?"}, {"answer": "", "question": "Describe your space needs, beyond wall space."}, {"answer": "", "question": "Would you be OK with other (non-visual) artists performing alongside your work?"}]', NULL, '2025-08-13 19:08:27.81227', '2026-01-08 17:14:12.891851');
INSERT INTO public."Questions" VALUES (467, 32, 303, '[{"answer": "David", "question": "*First Name"}, {"answer": "Wasik", "question": "*Last Name"}, {"answer": "646-296-1788", "question": "*Phone Number field::Phone"}, {"answer": "thewaz@thewaz.com", "question": "*Email field::Email"}, {"answer": "14201", "question": "*Zip field::ZIP Code"}, {"answer": "Solo percussion performance with audience participation featuring a guest painter during intermission", "question": "*Proposal Title"}, {"answer": "Music/Painting", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Live percussion performance/live painting.", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "I would like to perform on my front porch @ 143 Allen St. a solo percussion performance that has an audience participation component. During the intermission of said performance I would like to present some live percussion and painting, having the guest painter react to the sounds and having me react to the colors.", "question": "*Proposal Description"}, {"answer": "", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "Too much to list here...however, I will be in control of the environment I create seeing as that it will take place at my residence on my front porch.", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "Nothing.", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "Nothing.", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "I have enough space on my front porch.", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Yes", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "Painters/sculptor3s.", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "May 14 & 15 5-7pm. May 16 & 17 Noon ~ 3. ", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-27 09:13:22.998196', '2026-01-27 09:13:22.998196');
INSERT INTO public."Questions" VALUES (503, 9, 302, '[{"answer": "", "question": "*Integer field:: How many members are involved in this proposal?"}, {"answer": "", "question": "*List all names and roles of all core participants (this helps us avoid scheduling conflicts)"}, {"answer": "", "question": "URL field:: Do you have a preferred social media profile or handle to be tagged as a participant in our own promotion efforts leading up to or during the event dates? Enter them here. (note: any Refringe promotion must be considered as *amplifying*, not replacing, your own independent promotion, and is not guaranteed.)"}, {"answer": "", "question": "URL field:: Copy and paste the URL of your preferred social media account or personal homepage, to share with the public in event listings or other publishable materials."}, {"answer": "", "question": "URL field:: Do you have an image link? Enter it here."}, {"answer": "", "question": "URL field:: Do you have a video link with examples of your work? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event contain adult content?"}, {"answer": "", "question": "*Yes / No / Maybe field:: Are all participants 21+?"}, {"answer": "", "question": "*Provide a description of your event that only for organizers - be detailed (surprises are for audiences, not organizers)"}, {"answer": "", "question": "*Up to 125 chars field:: Provide a short description of your proposal, fit for print."}, {"answer": "", "question": "*Provide a description of your event for the web. This may be as long as you&#039;d like."}, {"answer": "", "question": "*What does ReFringe mean to you?"}, {"answer": "", "question": "Does your work involve AI ? If so, how? (There are no disqualifying answers. This question is for organizational purposes ONLY.)"}, {"answer": "", "question": "Miscellaneous?"}, {"answer": "", "question": "List of all known or potential other proposals that you or other members are or may be involved in, whether as proposers or as core members of. (This helps us avoid unintentional double bookings)"}]', NULL, '2026-01-31 16:46:36.727545', '2026-01-31 16:46:36.727545');
INSERT INTO public."Questions" VALUES (504, 5, 302, '[{"answer": "", "question": "Live Screening?"}, {"answer": "", "question": "Installation?"}, {"answer": "", "question": "Genre"}, {"answer": "", "question": "Preview urls + instructions."}, {"answer": "", "question": "Can you provide a viewing space?"}, {"answer": "", "question": "Can you provide a projector and/or screen?"}, {"answer": "", "question": "Director(s)"}, {"answer": "", "question": "Cast"}, {"answer": "", "question": "Crew"}, {"answer": "", "question": "Production Co."}]', NULL, '2026-01-31 16:46:36.728587', '2026-01-31 16:46:36.728587');
INSERT INTO public."Questions" VALUES (505, 4, 302, '[{"answer": "", "question": "*What themes and/or topics do you explore?"}, {"answer": "", "question": "Titles (and co-authorship, etc.) for works you would perform."}, {"answer": "", "question": "I do not wish to list titles, or it doesn''t apply to me."}, {"answer": "", "question": "Publisher/other affiliation."}]', NULL, '2026-01-31 16:46:36.729271', '2026-01-31 16:46:36.729271');
INSERT INTO public."Questions" VALUES (506, 6, 302, '[{"answer": "", "question": "Will you provide your own music playback?"}, {"answer": "", "question": "Would you be willing to incorporate improvising musicians into a scheduled dance event?"}, {"answer": "", "question": "Are you willing to perform with improvising musicians on a scheduled music event, even without a proper floor?"}, {"answer": "", "question": "What surfaces are you comfortable working on? Or not working on?"}, {"answer": "", "question": "Do you require a floor designed for dance?"}, {"answer": "", "question": "Can other performers share your gear?"}, {"answer": "", "question": "Does your act include video projection? (If so be mindful that you must bring your own equipment--unless otherwise prearranged.)"}, {"answer": "", "question": "Does your act require lighting or staging? (If so be mindful that the venue may not provide these.)"}]', NULL, '2026-01-31 16:46:36.729996', '2026-01-31 16:46:36.729996');
INSERT INTO public."Questions" VALUES (507, 2, 302, '[{"answer": "", "question": "I am/we are willing to play purely acoustic."}, {"answer": "", "question": "What venues has your band played? Can you give a gig history?"}, {"answer": "", "question": "Integer (1 - 10) field:: How loud are you?"}, {"answer": "", "question": "What groups or styles have you shared bills with?"}, {"answer": "", "question": "Instrumentation."}, {"answer": "", "question": "I/we requre a full PA."}, {"answer": "", "question": "When playing with a PA, how many vocal mics?"}, {"answer": "", "question": "I/we own a PA that others can use *and share* for shows?"}, {"answer": "", "question": "I/we can you provide amps or other equipment to share?"}, {"answer": "", "question": "I/we can provide drums to share."}, {"answer": "", "question": "I/we primarily perform as a DJ/laptopist/electronic instrumentalist."}, {"answer": "", "question": "I have DJ/mixing equipment I can share."}, {"answer": "", "question": "I or my groups'' members would be interested in improvising in larger (scheduled) collaborations?"}]', NULL, '2026-01-31 16:46:36.730659', '2026-01-31 16:46:36.730659');
INSERT INTO public."Questions" VALUES (508, 3, 302, '[{"answer": "", "question": "Director(s)"}, {"answer": "", "question": "Cast"}, {"answer": "", "question": "Crew"}, {"answer": "", "question": "Production Co."}, {"answer": "", "question": "If you need amplification or stereo playback, please see above (performance) questions."}, {"answer": "", "question": "List pieces and sizes."}, {"answer": "", "question": "Do you have gallery space prearranged?"}, {"answer": "", "question": "Is there a live performance aspect?"}, {"answer": "", "question": "Is this an installation work?"}, {"answer": "", "question": "Describe your space needs, beyond wall space."}, {"answer": "", "question": "Would you be OK with other (non-visual) artists performing alongside your work?"}]', NULL, '2026-01-31 16:46:36.731332', '2026-01-31 16:46:36.731332');
INSERT INTO public."Questions" VALUES (509, 30, 302, '[{"answer": "", "question": "What is your art medium?"}, {"answer": "", "question": "List pieces and sizes."}, {"answer": "", "question": "Do you have gallery space prearranged?"}, {"answer": "", "question": "Is there a live performance aspect?"}, {"answer": "", "question": "Is this an installation work?"}, {"answer": "", "question": "Describe your space needs, beyond wall space."}, {"answer": "", "question": "Would you be OK with other (non-visual) artists performing alongside your work?"}]', NULL, '2026-01-31 16:46:36.732026', '2026-01-31 16:46:36.732026');
INSERT INTO public."Questions" VALUES (457, 32, 295, '[{"answer": "Cyrt", "question": "*First Name"}, {"answer": "Rotterdam", "question": "*Last Name"}, {"answer": "716-123-1234", "question": "*Phone Number field::Phone"}, {"answer": "steelcrazybooking@gmail.com", "question": "*Email field::Email"}, {"answer": "12343", "question": "*Zip field::ZIP Code"}, {"answer": "Change ", "question": "*Proposal Title"}, {"answer": "Change ", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Change ", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "Change ", "question": "*Proposal Description"}, {"answer": "Change ", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "Change ", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "Change ", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "Change ", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "Change ", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Y", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "May ", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-19 18:06:33.139513', '2026-01-19 18:06:33.139513');
INSERT INTO public."Questions" VALUES (130, 9, 70, '[{"answer": "", "question": "*Yes / No::Are you from out of town?"}, {"answer": "", "question": "*Integer::How many are you?"}, {"answer": "", "question": "*What are your names and roles?"}, {"answer": "", "question": "URL::Do you have a Facebook link? Enter it here."}, {"answer": "", "question": "URL::Do you have a personal website link? Enter it here."}, {"answer": "", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "", "question": "*Are all people participating in your event of age 21+?"}, {"answer": "", "question": "*Provide a description of your event that only you can review."}, {"answer": "", "question": "*Up to 125 chars::Provide a description of your event for the news."}, {"answer": "", "question": "*Provide a description of your event for the web."}, {"answer": "", "question": "*What ReFringe ideals will be demonstrated by your event?"}, {"answer": "", "question": "Notes (miscellaneous)?"}]', NULL, '2025-06-07 03:31:49.176777', '2025-06-07 22:59:05.91463');
INSERT INTO public."Questions" VALUES (115, 9, 73, '[{"answer": "", "question": "*Yes / No::Are you from out of town?"}, {"answer": "", "question": "*Integer::How many are you?"}, {"answer": "", "question": "*What are your names and roles?"}, {"answer": "", "question": "URL::Do you have a Facebook link? Enter it here."}, {"answer": "", "question": "URL::Do you have a personal website link? Enter it here."}, {"answer": "", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "", "question": "*Are all people participating in your event of age 21+?"}, {"answer": "", "question": "*Provide a description of your event that only you can review."}, {"answer": "", "question": "*Up to 125 chars::Provide a description of your event for the news."}, {"answer": "", "question": "*Provide a description of your event for the web."}, {"answer": "", "question": "*What ReFringe ideals will be demonstrated by your event?"}, {"answer": "", "question": "Notes (miscellaneous)?"}]', NULL, '2025-06-04 17:31:57.343564', '2025-06-07 22:59:05.91463');
INSERT INTO public."Questions" VALUES (131, 9, 61, '[{"answer": "", "question": "*Yes / No::Are you from out of town?"}, {"answer": "", "question": "*Integer::How many are you?"}, {"answer": "", "question": "*What are your names and roles?"}, {"answer": "", "question": "URL::Do you have a Facebook link? Enter it here."}, {"answer": "", "question": "URL::Do you have a personal website link? Enter it here."}, {"answer": "", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "", "question": "*Are all people participating in your event of age 21+?"}, {"answer": "", "question": "*Provide a description of your event that only you can review."}, {"answer": "", "question": "*Up to 125 chars::Provide a description of your event for the news."}, {"answer": "", "question": "*Provide a description of your event for the web."}, {"answer": "", "question": "*What ReFringe ideals will be demonstrated by your event?"}, {"answer": "", "question": "Notes (miscellaneous)?"}]', NULL, '2025-06-07 03:32:53.617774', '2025-06-07 22:59:05.91463');
INSERT INTO public."Questions" VALUES (458, 32, 296, '[{"answer": "Steev", "question": "*First Name"}, {"answer": "boochinski", "question": "*Last Name"}, {"answer": "716-430-5090", "question": "*Phone Number field::Phone"}, {"answer": "steev@invicta.org", "question": "*Email field::Email"}, {"answer": "15160", "question": "*Zip field::ZIP Code"}, {"answer": "unxtructured time", "question": "*Proposal Title"}, {"answer": "all disciples invited", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "visual art ++ poi", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "body parts dripping paint onto a large canvas w/house music playing", "question": "*Proposal Description"}, {"answer": "", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "speakers, paint canvas, body parts", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "electric, sound setup possible", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "10 x 20 feet", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "yes", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "all", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "each day", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-19 18:11:37.870463', '2026-01-19 18:11:37.870463');
INSERT INTO public."Questions" VALUES (119, 9, 74, '[{"answer": "", "question": "*Yes / No::Are you from out of town?"}, {"answer": "", "question": "*Integer::How many are you?"}, {"answer": "", "question": "*What are your names and roles?"}, {"answer": "", "question": "URL::Do you have a Facebook link? Enter it here."}, {"answer": "", "question": "URL::Do you have a personal website link? Enter it here."}, {"answer": "", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "", "question": "*Are all people participating in your event of age 21+?"}, {"answer": "", "question": "*Provide a description of your event that only you can review."}, {"answer": "", "question": "*Up to 125 chars::Provide a description of your event for the news."}, {"answer": "", "question": "*Provide a description of your event for the web."}, {"answer": "", "question": "*What ReFringe ideals will be demonstrated by your event?"}, {"answer": "", "question": "Notes (miscellaneous)?"}]', NULL, '2025-06-04 17:44:07.358692', '2025-06-07 22:59:05.91463');
INSERT INTO public."Questions" VALUES (135, 9, 76, '[{"answer": "", "question": "*Yes / No::Are you from out of town?"}, {"answer": "", "question": "*Integer::How many are you?"}, {"answer": "", "question": "*What are your names and roles?"}, {"answer": "", "question": "URL::Do you have a Facebook link? Enter it here."}, {"answer": "", "question": "URL::Do you have a personal website link? Enter it here."}, {"answer": "", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "", "question": "*Are all people participating in your event of age 21+?"}, {"answer": "", "question": "*Provide a description of your event that only you can review."}, {"answer": "", "question": "*Up to 125 chars::Provide a description of your event for the news."}, {"answer": "", "question": "*Provide a description of your event for the web."}, {"answer": "", "question": "*What ReFringe ideals will be demonstrated by your event?"}, {"answer": "", "question": "Notes (miscellaneous)?"}]', NULL, '2025-06-07 03:40:18.743277', '2025-06-07 22:59:05.91463');
INSERT INTO public."Questions" VALUES (511, 1, 302, '[{"answer": "", "question": "Performance duration - minimum"}, {"answer": "", "question": "Performance duration - maximum"}, {"answer": "", "question": "Setup time."}, {"answer": "", "question": "Teardown time."}, {"answer": "", "question": "What is the minimum amount of space for your performance?"}, {"answer": "", "question": "The majority of our performance is original works."}, {"answer": "", "question": "We are interested in playing outside."}, {"answer": "", "question": "We have a venue prearranged."}, {"answer": "", "question": "Our act requires seating."}, {"answer": "", "question": "I am willing to perform in non-traditional settings."}, {"answer": "", "question": "I am willing to perform at opening/closing ceremonies."}, {"answer": "", "question": "We would like to project images."}, {"answer": "", "question": "I book or otherwise organize bills/shows (not just those involving my own group)."}, {"answer": "", "question": "I''d like to help run a show."}, {"answer": "", "question": "Are you open to artists in different modalities sharing your time/space?"}]', NULL, '2026-01-31 16:46:36.733426', '2026-01-31 16:46:36.733426');
INSERT INTO public."Questions" VALUES (137, 9, 75, '[{"answer": "", "question": "*Yes / No::Are you from out of town?"}, {"answer": "", "question": "*Integer::How many are you?"}, {"answer": "", "question": "*What are your names and roles?"}, {"answer": "", "question": "URL::Do you have a Facebook link? Enter it here."}, {"answer": "", "question": "URL::Do you have a personal website link? Enter it here."}, {"answer": "", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "", "question": "*Are all people participating in your event of age 21+?"}, {"answer": "", "question": "*Provide a description of your event that only you can review."}, {"answer": "", "question": "*Up to 125 chars::Provide a description of your event for the news."}, {"answer": "", "question": "*Provide a description of your event for the web."}, {"answer": "", "question": "*What ReFringe ideals will be demonstrated by your event?"}, {"answer": "", "question": "Notes (miscellaneous)?"}]', NULL, '2025-06-07 03:41:44.582473', '2025-06-07 22:59:05.91463');
INSERT INTO public."Questions" VALUES (513, 28, 302, '[{"answer": "", "question": "Delimited List field:: Theme Tag(s)--For Organizer Use Only."}]', NULL, '2026-01-31 16:46:36.734798', '2026-01-31 16:46:36.734798');
INSERT INTO public."Questions" VALUES (514, 26, 302, '[{"answer": "", "question": "Do you need a place to change clothes/costumes, i.e, a dressing room?"}]', NULL, '2026-01-31 16:46:36.735464', '2026-01-31 16:46:36.735464');
INSERT INTO public."Questions" VALUES (516, 8, 302, '[{"answer": "", "question": "Would you busk or perform, or both?"}, {"answer": "", "question": "Do you have experience busking?"}, {"answer": "", "question": "Do you have/can you obtain a busking license?"}, {"answer": "", "question": "Does your act roam?"}, {"answer": "", "question": "If necessary, do you have a mobile power source?"}, {"answer": "", "question": "Does your act rely on interaction or improv?"}, {"answer": "", "question": "Is your act appropriate for Kid-fringement and/or Bidwell Saturday Markets, or similar?"}, {"answer": "", "question": "If necessary, do you have a portable amp?"}]', NULL, '2026-01-31 16:46:36.736843', '2026-01-31 16:46:36.736843');
INSERT INTO public."Questions" VALUES (520, 32, 314, '[{"answer": "Kimm", "question": "*First Name"}, {"answer": "Moon", "question": "*Last Name"}, {"answer": "716-606-8317", "question": "*Phone Number field::Phone"}, {"answer": "Ornatepelt@yahoo.com", "question": "*Email field::Email"}, {"answer": "14212", "question": "*Zip field::ZIP Code"}, {"answer": "DIY Art Gallery ", "question": "*Proposal Title"}, {"answer": "Visual, fine arts", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Live art making ", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "A temporary 4 day DIY evolving art gallery open for anyone to view and add on to.", "question": "*Proposal Description"}, {"answer": "Hosted by inspiration club of any https://inspiration-club-of-wny.org\n\n", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "Anything to make art. A tent. Chairs.misc supplies. Walls to hang art chicken wire", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "Nope we make due", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "Pop up tent. Misc. Advertising for event", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "10 by 10 tent and space around it to gather", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Y", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "Idk", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "I would like to see if the installment event running for the duration of the event all 4 days", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-31 18:33:59.719724', '2026-01-31 18:33:59.719724');
INSERT INTO public."Questions" VALUES (221, 30, 113, '[{"answer": "Mixed-media", "question": "What is your art medium?"}, {"answer": "At least 25 paintings-sizes 8inchx10inch-18inchx24inch", "question": "List pieces and sizes."}, {"answer": "No", "question": "Do you have gallery space prearranged?"}, {"answer": "Yes", "question": "Is there a live performance aspect?"}, {"answer": "No", "question": "Is this an installation work?"}, {"answer": "An inside venue or can bring my tent for outside. Available electrical outlets preferred.", "question": "Describe your space needs, beyond wall space."}, {"answer": "Yes", "question": "Would you be OK with other (non-visual) artists performing alongside your work?"}, {"answer": "N/A", "question": "Miscellaneous?"}]', NULL, '2025-10-17 11:39:20.611388', '2025-11-04 17:26:02.562289');
INSERT INTO public."Questions" VALUES (521, 5, 240, '[{"answer": "", "question": "Live Screening?"}, {"answer": "", "question": "Installation?"}, {"answer": "", "question": "Genre"}, {"answer": "", "question": "Preview urls + instructions."}, {"answer": "", "question": "Can you provide a viewing space?"}, {"answer": "", "question": "Can you provide a projector and/or screen?"}, {"answer": "", "question": "Director(s)"}, {"answer": "", "question": "Cast"}, {"answer": "", "question": "Crew"}, {"answer": "", "question": "Production Co."}]', NULL, '2026-01-31 18:48:47.205765', '2026-01-31 18:48:47.205765');
INSERT INTO public."Questions" VALUES (522, 4, 240, '[{"answer": "", "question": "*What themes and/or topics do you explore?"}, {"answer": "", "question": "Titles (and co-authorship, etc.) for works you would perform."}, {"answer": "", "question": "I do not wish to list titles, or it doesn''t apply to me."}, {"answer": "", "question": "Publisher/other affiliation."}]', NULL, '2026-01-31 18:48:47.206731', '2026-01-31 18:48:47.206731');
INSERT INTO public."Questions" VALUES (523, 6, 240, '[{"answer": "", "question": "Will you provide your own music playback?"}, {"answer": "", "question": "Would you be willing to incorporate improvising musicians into a scheduled dance event?"}, {"answer": "", "question": "Are you willing to perform with improvising musicians on a scheduled music event, even without a proper floor?"}, {"answer": "", "question": "What surfaces are you comfortable working on? Or not working on?"}, {"answer": "", "question": "Do you require a floor designed for dance?"}, {"answer": "", "question": "Can other performers share your gear?"}, {"answer": "", "question": "Does your act include video projection? (If so be mindful that you must bring your own equipment--unless otherwise prearranged.)"}, {"answer": "", "question": "Does your act require lighting or staging? (If so be mindful that the venue may not provide these.)"}]', NULL, '2026-01-31 18:48:47.207617', '2026-01-31 18:48:47.207617');
INSERT INTO public."Questions" VALUES (524, 3, 240, '[{"answer": "", "question": "Director(s)"}, {"answer": "", "question": "Cast"}, {"answer": "", "question": "Crew"}, {"answer": "", "question": "Production Co."}, {"answer": "", "question": "If you need amplification or stereo playback, please see above (performance) questions."}, {"answer": "", "question": "List pieces and sizes."}, {"answer": "", "question": "Do you have gallery space prearranged?"}, {"answer": "", "question": "Is there a live performance aspect?"}, {"answer": "", "question": "Is this an installation work?"}, {"answer": "", "question": "Describe your space needs, beyond wall space."}, {"answer": "", "question": "Would you be OK with other (non-visual) artists performing alongside your work?"}]', NULL, '2026-01-31 18:48:47.208489', '2026-01-31 18:48:47.208489');
INSERT INTO public."Questions" VALUES (525, 30, 240, '[{"answer": "", "question": "What is your art medium?"}, {"answer": "", "question": "List pieces and sizes."}, {"answer": "", "question": "Do you have gallery space prearranged?"}, {"answer": "", "question": "Is there a live performance aspect?"}, {"answer": "", "question": "Is this an installation work?"}, {"answer": "", "question": "Describe your space needs, beyond wall space."}, {"answer": "", "question": "Would you be OK with other (non-visual) artists performing alongside your work?"}]', NULL, '2026-01-31 18:48:47.209358', '2026-01-31 18:48:47.209358');
INSERT INTO public."Questions" VALUES (530, 9, 268, '[{"answer": "", "question": "*Integer field:: How many members are involved in this proposal?"}, {"answer": "", "question": "*List all names and roles of all core participants (this helps us avoid scheduling conflicts)"}, {"answer": "", "question": "URL field:: Do you have a preferred social media profile or handle to be tagged as a participant in our own promotion efforts leading up to or during the event dates? Enter them here. (note: any Refringe promotion must be considered as *amplifying*, not replacing, your own independent promotion, and is not guaranteed.)"}, {"answer": "", "question": "URL field:: Copy and paste the URL of your preferred social media account or personal homepage, to share with the public in event listings or other publishable materials."}, {"answer": "", "question": "URL field:: Do you have an image link? Enter it here."}, {"answer": "", "question": "URL field:: Do you have a video link with examples of your work? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event contain adult content?"}, {"answer": "", "question": "*Yes / No / Maybe field:: Are all participants 21+?"}, {"answer": "", "question": "*Provide a description of your event that only for organizers - be detailed (surprises are for audiences, not organizers)"}, {"answer": "", "question": "*Up to 125 chars field:: Provide a short description of your proposal, fit for print."}, {"answer": "", "question": "*Provide a description of your event for the web. This may be as long as you&#039;d like."}, {"answer": "", "question": "*What does ReFringe mean to you?"}, {"answer": "", "question": "Does your work involve AI ? If so, how? (There are no disqualifying answers. This question is for organizational purposes ONLY.)"}, {"answer": "", "question": "Miscellaneous?"}, {"answer": "", "question": "List of all known or potential other proposals that you or other members are or may be involved in, whether as proposers or as core members of. (This helps us avoid unintentional double bookings)"}]', NULL, '2026-01-31 20:38:22.277824', '2026-01-31 20:38:22.277824');
INSERT INTO public."Questions" VALUES (531, 5, 268, '[{"answer": "", "question": "Live Screening?"}, {"answer": "", "question": "Installation?"}, {"answer": "", "question": "Genre"}, {"answer": "", "question": "Preview urls + instructions."}, {"answer": "", "question": "Can you provide a viewing space?"}, {"answer": "", "question": "Can you provide a projector and/or screen?"}, {"answer": "", "question": "Director(s)"}, {"answer": "", "question": "Cast"}, {"answer": "", "question": "Crew"}, {"answer": "", "question": "Production Co."}]', NULL, '2026-01-31 20:38:22.278895', '2026-01-31 20:38:22.278895');
INSERT INTO public."Questions" VALUES (532, 4, 268, '[{"answer": "", "question": "*What themes and/or topics do you explore?"}, {"answer": "", "question": "Titles (and co-authorship, etc.) for works you would perform."}, {"answer": "", "question": "I do not wish to list titles, or it doesn''t apply to me."}, {"answer": "", "question": "Publisher/other affiliation."}]', NULL, '2026-01-31 20:38:22.279784', '2026-01-31 20:38:22.279784');
INSERT INTO public."Questions" VALUES (533, 6, 268, '[{"answer": "", "question": "Will you provide your own music playback?"}, {"answer": "", "question": "Would you be willing to incorporate improvising musicians into a scheduled dance event?"}, {"answer": "", "question": "Are you willing to perform with improvising musicians on a scheduled music event, even without a proper floor?"}, {"answer": "", "question": "What surfaces are you comfortable working on? Or not working on?"}, {"answer": "", "question": "Do you require a floor designed for dance?"}, {"answer": "", "question": "Can other performers share your gear?"}, {"answer": "", "question": "Does your act include video projection? (If so be mindful that you must bring your own equipment--unless otherwise prearranged.)"}, {"answer": "", "question": "Does your act require lighting or staging? (If so be mindful that the venue may not provide these.)"}]', NULL, '2026-01-31 20:38:22.280578', '2026-01-31 20:38:22.280578');
INSERT INTO public."Questions" VALUES (159, 6, 85, '[{"answer": "No", "question": "Do you require a floor designed for dance?"}, {"answer": "Yes", "question": "Will you provide your own music playback?"}, {"answer": "No", "question": "Would you be willing to incorporate improvising musicians into a scheduled dance event?"}, {"answer": "Yes", "question": "Can other performers share your gear?"}, {"answer": "No", "question": "Are you willing to perform with improvising musicians on a scheduled music event, even without a proper floor?"}, {"answer": "No", "question": "Does your act require video projection?"}, {"answer": "Yes", "question": "Does your act require lighting or staging?"}]', NULL, '2025-07-16 17:58:20.3606', '2025-11-19 17:37:50.747153');
INSERT INTO public."Questions" VALUES (460, 32, 298, '[{"answer": "Beth", "question": "*First Name"}, {"answer": "Carr", "question": "*Last Name"}, {"answer": "716-275-3871", "question": "*Phone Number field::Phone"}, {"answer": "Beth.carr6@gmail.com", "question": "*Email field::Email"}, {"answer": "14075", "question": "*Zip field::ZIP Code"}, {"answer": "Tba", "question": "*Proposal Title"}, {"answer": "Visual arts", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Any/all paint mediums, mosaic, braided rug making, crochet ", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "Tba", "question": "*Proposal Description"}, {"answer": "Website-://happyrayofsunshine.bigcartel.com/\nInstagram-@happy_ray_of_sunshine\nFbpage-Bethcarrart", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "Blacklights can be used but not needed!", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "Tba", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "Pop up tent, black lights", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "Tba", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Yes", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "Any painting,mosaic,crochet ", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "All hrs. available, all days! ", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-19 18:32:47.260868', '2026-01-19 18:32:47.260868');
INSERT INTO public."Questions" VALUES (534, 2, 268, '[{"answer": "", "question": "I am/we are willing to play purely acoustic."}, {"answer": "", "question": "What venues has your band played? Can you give a gig history?"}, {"answer": "", "question": "Integer (1 - 10) field:: How loud are you?"}, {"answer": "", "question": "What groups or styles have you shared bills with?"}, {"answer": "", "question": "Instrumentation."}, {"answer": "", "question": "I/we requre a full PA."}, {"answer": "", "question": "When playing with a PA, how many vocal mics?"}, {"answer": "", "question": "I/we own a PA that others can use *and share* for shows?"}, {"answer": "", "question": "I/we can you provide amps or other equipment to share?"}, {"answer": "", "question": "I/we can provide drums to share."}, {"answer": "", "question": "I/we primarily perform as a DJ/laptopist/electronic instrumentalist."}, {"answer": "", "question": "I have DJ/mixing equipment I can share."}, {"answer": "", "question": "I or my groups'' members would be interested in improvising in larger (scheduled) collaborations?"}]', NULL, '2026-01-31 20:38:22.281336', '2026-01-31 20:38:22.281336');
INSERT INTO public."Questions" VALUES (535, 3, 268, '[{"answer": "", "question": "Director(s)"}, {"answer": "", "question": "Cast"}, {"answer": "", "question": "Crew"}, {"answer": "", "question": "Production Co."}, {"answer": "", "question": "If you need amplification or stereo playback, please see above (performance) questions."}, {"answer": "", "question": "List pieces and sizes."}, {"answer": "", "question": "Do you have gallery space prearranged?"}, {"answer": "", "question": "Is there a live performance aspect?"}, {"answer": "", "question": "Is this an installation work?"}, {"answer": "", "question": "Describe your space needs, beyond wall space."}, {"answer": "", "question": "Would you be OK with other (non-visual) artists performing alongside your work?"}]', NULL, '2026-01-31 20:38:22.282142', '2026-01-31 20:38:22.282142');
INSERT INTO public."Questions" VALUES (150, 9, 85, '[{"answer": "No", "question": "*Yes / No::Are you from out of town?"}, {"answer": "2", "question": "*Integer::How many are you?"}, {"answer": "Ayame: Bell ringer/dancer\nNick: Lyre player/dancer", "question": "*What are your names and roles?"}, {"answer": "", "question": "URL::Do you have a Facebook link? Enter it here."}, {"answer": "https://bentenmusiccenter.squarespace.com", "question": "URL::Do you have a personal website link? Enter it here."}, {"answer": "", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "https://m.soundcloud.com/sukhavati-1/the-other-shore\n", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "Yes", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "No", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "Yes", "question": "*Are all people participating in your event of age 21+?"}, {"answer": "Participatory consciousness-altering chant and movement, accompanied by harmonium, bells, gongs, drum, etc. with projected kaleidoscopic visuals.", "question": "*Provide a description of your event that only you can review."}, {"answer": "A sonic spell that will undo the mass media curse through harmonizing body and mind.", "question": "*Up to 125 chars::Provide a description of your event for the news."}, {"answer": "A sonic spell that will undo the mass media curse through harmonizing body and mind.", "question": "*Provide a description of your event for the web."}, {"answer": "Collaboration and Participatory", "question": "*What ReFringe ideals will be demonstrated by your event?"}, {"answer": "", "question": "Notes (miscellaneous)?"}]', NULL, '2025-06-18 18:31:05.07894', '2025-11-19 17:37:50.744736');
INSERT INTO public."Questions" VALUES (218, 4, 113, '[{"answer": "Stream of conciousness, sci-fi surrealism, social satire", "question": "*What themes and/or topics do you explore?"}, {"answer": "Paintings from Chris McGee''s “Words and Pictures” series; percussion/drums performed by Chris McGee; songs, stories by Meria (Haunted Gypsy).", "question": "Titles (and co-authorship, etc.) for works you would perform."}, {"answer": "N/A", "question": "I do not wish to list titles, or it doesn''t apply to me."}, {"answer": "N/A", "question": "Publisher/other affiliation."}, {"answer": "N/A", "question": "Miscellaneous?"}]', NULL, '2025-10-17 11:39:20.608705', '2025-11-04 17:26:02.559491');
INSERT INTO public."Questions" VALUES (220, 1, 113, '[{"answer": "1 hour", "question": "Performance duration - minimum"}, {"answer": "3 hours at a time", "question": "Performance duration - maximum"}, {"answer": "20 minutes ", "question": "Setup time."}, {"answer": "20 minutes", "question": "Teardown time."}, {"answer": "Enough for 2people with voice, guitar, hand-drum", "question": "What is the minimum amount of space for your performance?"}, {"answer": "Yes", "question": "The majority of our performance is original works."}, {"answer": "Yes", "question": "We are interested in playing outside."}, {"answer": "No", "question": "We have a venue prearranged."}, {"answer": "No", "question": "Our act requires seating."}, {"answer": "Yes", "question": "I am willing to perform in non-traditional settings."}, {"answer": "Yes", "question": "I am willing to perform at opening/closing ceremonies."}, {"answer": "No", "question": "We would like to project images."}, {"answer": "Yes", "question": "I book or otherwise organize bills/shows (not just those involving my own group)."}, {"answer": "Possibly.", "question": "I''d like to help run a show."}, {"answer": "Yes", "question": "Are you open to artists in different modalities sharing your time/space?"}, {"answer": "N/A", "question": "Miscellaneous?"}]', NULL, '2025-10-17 11:39:20.610388', '2025-11-04 17:26:02.561598');
INSERT INTO public."Questions" VALUES (222, 9, 113, '[{"answer": "2", "question": "*Integer::How many members are involved in this proposal?"}, {"answer": "Chris McGee, Meria", "question": "*List all names of artists."}, {"answer": "https://m.facebook.com/chris.mcgee.16/", "question": "URL::Do you have a Facebook link? Enter it here."}, {"answer": "N/A", "question": "URL::Do you have a personal website link? Enter it here."}, {"answer": "N/A", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "https://www.youtube.com/watch?v=Nf0Zw1gmfj8", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "Ig:chris.mcgee.16", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "https://www.tiktok.com/@hauntedgypsy/video/7472259304146603310", "question": "Any other links to show us?"}, {"answer": "No", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "Yes", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "Yes", "question": "*Are all people participating in your event of age 21+?"}, {"answer": "Collage paintings and music:expressionistic storytelling", "question": "*Provide a description of your event that only you can review."}, {"answer": "Collage painting accompanied by rock music, electric guitar, drums and percussion, expressionistic storytelling.", "question": "*Up to 125 chars::Provide a description of your event for the news."}, {"answer": "Collage paintings by local artist and musician Chris McGee will be accompanied by live rock music performed by Haunted Gypsy, including McGee on drums and percussion and Meria on guitar and vocals, providing a unique, expressionistic storytelling event.", "question": "*Provide a description of your event for the web."}, {"answer": "Collaboration, subversive themes, artistic integrity, multimedia, creative, innovative.", "question": "*What ReFringe ideals will be demonstrated by your event?"}, {"answer": "N/A", "question": "Does your work involve AI ? If so, how? (There are no disqualifying answers. This question is for organizational purposes ONLY.)"}, {"answer": "N/A", "question": "Miscellaneous?"}]', NULL, '2025-10-17 18:57:15.914997', '2025-11-04 17:26:02.556651');
INSERT INTO public."Questions" VALUES (219, 2, 113, '[{"answer": "If necessary, yes.", "question": "I am/we are willing to play purely acoustic."}, {"answer": "Penny Lane, House of Guitars, Buffalo, Lancaster, Kenmore Porch Fests, Couzins, Area 54, Boulder Coffee, etc.", "question": "What venues has your band played? Can you give a gig history?"}, {"answer": "7", "question": "Integer (1 - 10)::How loud are you?"}, {"answer": "Stingrays, Sardine, Sidekick, Jane -- rock, pop, grunge, post-punk, progressive rock.", "question": "What groups or styles have you shared bills with?"}, {"answer": "Guitar, drums, percussion, vocals.", "question": "Instrumentation."}, {"answer": "If available. We also have our own equipment too.", "question": "I/we requre a full PA."}, {"answer": "1", "question": "When playing with a PA, how many vocal mics?"}, {"answer": "N/A", "question": "I/we own a PA that others can use *and share* for shows?"}, {"answer": "N/A", "question": "I/we can you provide amps or other equipment to share?"}, {"answer": "N/A", "question": "I/we can provide drums to share."}, {"answer": "N/A", "question": "I/we primarily perform as a DJ/laptopist/electronic instrumentalist."}, {"answer": "N/A", "question": "I have DJ/mixing equipment I can share."}, {"answer": "Possibly.", "question": "I or my groups'' members would be interested in improvising in larger (scheduled) collaborations?"}, {"answer": "N/A", "question": "Miscellaneous?"}]', NULL, '2025-10-17 11:39:20.609456', '2025-11-04 17:26:02.560923');
INSERT INTO public."Questions" VALUES (536, 30, 268, '[{"answer": "", "question": "What is your art medium?"}, {"answer": "", "question": "List pieces and sizes."}, {"answer": "", "question": "Do you have gallery space prearranged?"}, {"answer": "", "question": "Is there a live performance aspect?"}, {"answer": "", "question": "Is this an installation work?"}, {"answer": "", "question": "Describe your space needs, beyond wall space."}, {"answer": "", "question": "Would you be OK with other (non-visual) artists performing alongside your work?"}]', NULL, '2026-01-31 20:38:22.283012', '2026-01-31 20:38:22.283012');
INSERT INTO public."Questions" VALUES (236, 31, 0, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2025-11-19 17:40:22.876596', '2026-01-31 23:30:20.362787');
INSERT INTO public."Questions" VALUES (160, 2, 85, '[{"answer": "Yes", "question": "I am/we are willing to play purely acoustic."}, {"answer": "NAMM", "question": "What venues has your band played? Can you give a gig history?"}, {"answer": "3", "question": "Integer (1 - 10)::How loud are you?"}, {"answer": "World music", "question": "What groups or styles have you shared bills with?"}, {"answer": "Lyre and handbells", "question": "Instrumentation."}, {"answer": "No", "question": "I/we requre a full PA."}, {"answer": "N/A", "question": "When playing with a PA, how many vocal mics?"}, {"answer": "N/A", "question": "I/we own a PA that others can use *and share* for shows?"}, {"answer": "N/A", "question": "I/we can you provide amps or other equipment to share?"}, {"answer": "Yes", "question": "I/we can provide drums to share."}, {"answer": "No", "question": "I/we primarily perform as a DJ/laptopist/electronic instrumentalist."}, {"answer": "No", "question": "I have DJ/mixing equipment I can share."}, {"answer": "No", "question": "I or my groups'' members would be interested in improvising in larger (scheduled) collaborations?"}]', NULL, '2025-07-16 17:58:20.361465', '2025-11-19 17:37:50.747796');
INSERT INTO public."Questions" VALUES (538, 1, 268, '[{"answer": "", "question": "Performance duration - minimum"}, {"answer": "", "question": "Performance duration - maximum"}, {"answer": "", "question": "Setup time."}, {"answer": "", "question": "Teardown time."}, {"answer": "", "question": "What is the minimum amount of space for your performance?"}, {"answer": "", "question": "The majority of our performance is original works."}, {"answer": "", "question": "We are interested in playing outside."}, {"answer": "", "question": "We have a venue prearranged."}, {"answer": "", "question": "Our act requires seating."}, {"answer": "", "question": "I am willing to perform in non-traditional settings."}, {"answer": "", "question": "I am willing to perform at opening/closing ceremonies."}, {"answer": "", "question": "We would like to project images."}, {"answer": "", "question": "I book or otherwise organize bills/shows (not just those involving my own group)."}, {"answer": "", "question": "I''d like to help run a show."}, {"answer": "", "question": "Are you open to artists in different modalities sharing your time/space?"}]', NULL, '2026-01-31 20:38:22.284572', '2026-01-31 20:38:22.284572');
INSERT INTO public."Questions" VALUES (540, 28, 268, '[{"answer": "", "question": "Delimited List field:: Theme Tag(s)--For Organizer Use Only."}]', NULL, '2026-01-31 20:38:22.286453', '2026-01-31 20:38:22.286453');
INSERT INTO public."Questions" VALUES (541, 26, 268, '[{"answer": "", "question": "Do you need a place to change clothes/costumes, i.e, a dressing room?"}]', NULL, '2026-01-31 20:38:22.287249', '2026-01-31 20:38:22.287249');
INSERT INTO public."Questions" VALUES (302, 32, 230, '[{"answer": "Poo", "question": "*First Name"}, {"answer": "Poop", "question": "*Last Name"}, {"answer": "7165675309", "question": "*Phone Number:: Phone"}, {"answer": "Superbitch@gmail.com", "question": "*Email:: Email"}, {"answer": "14201", "question": "*ZIP Code"}, {"answer": "Fuckity fuck", "question": "*Project Title"}, {"answer": "Visual", "question": "*CommaSemiColonList:: Discipline(s)\nEx: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Projection", "question": "*CommaSemiColonList:: Genre(s) Within Your Discipline(s)\nEx: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines—such as movement/theatre or music/visual art—please list the genre and sub-genre for each discipline."}, {"answer": "Super fuck", "question": "*Proposal Description"}, {"answer": "", "question": "Work Sample Links (1–3)\nShare one to three links to examples of your work (if available)."}, {"answer": "Pwople", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "None", "question": "*Equipment You Need Provided by Us\nEx: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "", "question": "Equipment You Could Provide\nExamples: sound gear, lighting gear, pop-up tent."}, {"answer": "Any", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Yes", "question": "*Yes / No / Maybe:: Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "All", "question": "*AVAILABILITY (MAY 14–17)\nBe as specific as possible."}]', NULL, '2025-12-03 17:38:08.195723', '2025-12-03 17:38:08.195723');
INSERT INTO public."Questions" VALUES (542, 7, 268, '[{"answer": "", "question": "Please list website addresses or URLs to music/art repositories."}]', NULL, '2026-01-31 20:38:22.28798', '2026-01-31 20:38:22.28798');
INSERT INTO public."Questions" VALUES (543, 8, 268, '[{"answer": "", "question": "Would you busk or perform, or both?"}, {"answer": "", "question": "Do you have experience busking?"}, {"answer": "", "question": "Do you have/can you obtain a busking license?"}, {"answer": "", "question": "Does your act roam?"}, {"answer": "", "question": "If necessary, do you have a mobile power source?"}, {"answer": "", "question": "Does your act rely on interaction or improv?"}, {"answer": "", "question": "Is your act appropriate for Kid-fringement and/or Bidwell Saturday Markets, or similar?"}, {"answer": "", "question": "If necessary, do you have a portable amp?"}]', NULL, '2026-01-31 20:38:22.288698', '2026-01-31 20:38:22.288698');
INSERT INTO public."Questions" VALUES (468, 32, 304, '[{"answer": "Bud", "question": "*First Name"}, {"answer": "Redding", "question": "*Last Name"}, {"answer": "716-512-1931", "question": "*Phone Number field::Phone"}, {"answer": "DJBud61@gmail.com", "question": "*Email field::Email"}, {"answer": "14217-1222", "question": "*Zip field::ZIP Code"}, {"answer": "The Time Crisis of 2041", "question": "*Proposal Title"}, {"answer": "Film/Video, Visual Arts, Music", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Synth music, sci-fi videos", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "Video of several events that might occur during a time crisis.", "question": "*Proposal Description"}, {"answer": "Curt has seen my videos and such - I can provide links at a later date if needed.", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "Laptop / phone / projector / synth & amp", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "Electricity, projector (if possible)", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "Sound gear & projector (if needed)", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "Minimal - somewhere to project", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "N/A", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "Music w/ my videos", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "Available throughout most evenings on those days.", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-27 09:57:23.429704', '2026-01-27 09:57:23.429704');
INSERT INTO public."Questions" VALUES (303, 32, 231, '[{"answer": "Curtn", "question": "*First Name"}, {"answer": "Rott", "question": "*Last Name"}, {"answer": "716-602-6666", "question": "*Phone Number:: Phone"}, {"answer": "Drin@gmail.com", "question": "*Email:: Email"}, {"answer": "14212", "question": "*ZIP Code"}, {"answer": "Schmega", "question": "*Project Title"}, {"answer": "Music, theatre", "question": "*CommaSemiColonList:: Discipline(s)\nEx: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Rock, improv ", "question": "*CommaSemiColonList:: Genre(s) Within Your Discipline(s)\nEx: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines—such as movement/theatre or music/visual art—please list the genre and sub-genre for each discipline."}, {"answer": "Bag of fart", "question": "*Proposal Description"}, {"answer": "No", "question": "Work Sample Links (1–3)\nShare one to three links to examples of your work (if available)."}, {"answer": "Shootty", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "Skdjvjg", "question": "*Equipment You Need Provided by Us\nEx: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "Gear", "question": "Equipment You Could Provide\nExamples: sound gear, lighting gear, pop-up tent."}, {"answer": "Air", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Yes", "question": "*Yes / No / Maybe:: Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "Yes", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "May 14 17", "question": "*AVAILABILITY (MAY 14–17)\nBe as specific as possible."}]', NULL, '2025-12-03 17:41:22.326873', '2025-12-03 17:41:22.326873');
INSERT INTO public."Questions" VALUES (7, 7, 0, '[{"answer": "", "question": "Please list website addresses or URLs to music/art repositories."}]', NULL, '2025-01-27 19:19:04.458252', '2026-01-31 20:37:25.364581');
INSERT INTO public."Questions" VALUES (547, 7, 311, '[{"answer": "", "question": "Please list website addresses or URLs to music/art repositories."}]', NULL, '2026-01-31 20:38:32.462287', '2026-01-31 20:38:32.462287');
INSERT INTO public."Questions" VALUES (548, 31, 268, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-01-31 23:31:45.953944', '2026-01-31 23:31:45.953944');
INSERT INTO public."Questions" VALUES (549, 34, 268, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-01-31 23:31:45.955034', '2026-01-31 23:31:45.955034');
INSERT INTO public."Questions" VALUES (550, 35, 268, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-01-31 23:31:45.955763', '2026-01-31 23:31:45.955763');
INSERT INTO public."Questions" VALUES (301, 9, 228, '[{"answer": "1", "question": "*Integer::How many members are involved in this proposal?"}, {"answer": "Jeannette knop ", "question": "*List all names of artists."}, {"answer": "", "question": "URL::Do you have a Facebook link? Enter it here."}, {"answer": "", "question": "URL::Do you have a personal website link? Enter it here."}, {"answer": "", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "Yes", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "No", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "Yes", "question": "*Are all people participating in your event of age 21+?"}, {"answer": "Whistling while you work", "question": "*Provide a description of your event that only you can review."}, {"answer": "Booboo cachu", "question": "*Up to 125 chars::Provide a description of your event for the news."}, {"answer": "Hoo", "question": "*Provide a description of your event for the web."}, {"answer": "Fuck everyone", "question": "*What does ReFringe mean to you?"}, {"answer": "", "question": "Does your work involve AI ? If so, how? (There are no disqualifying answers. This question is for organizational purposes ONLY.)"}, {"answer": "", "question": "Miscellaneous?"}]', NULL, '2025-12-03 17:32:29.319465', '2025-12-03 17:34:50.972611');
INSERT INTO public."Questions" VALUES (402, 32, 277, '[{"answer": "aaa", "question": "*First Name"}, {"answer": "aaa", "question": "*Last Name"}, {"answer": "111-111-1111", "question": "*Phone Number field:: Phone"}, {"answer": "www@ggg.ccc", "question": "*Email field:: Email"}, {"answer": "12345", "question": "*Zip field:: ZIP Code"}, {"answer": "aaa", "question": "*Proposal Title"}, {"answer": "aaa", "question": "*Delimited List field:: Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "aaa", "question": "*Delimited List field:: Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines—such as movement/theatre or music/visual art—please list the genre and sub-genre for each discipline."}, {"answer": "aaa", "question": "*Proposal Description"}, {"answer": "", "question": "Delimited List field:: Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "aaa", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "aaa", "question": "*Delimited List field:: Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "", "question": "Delimited List field:: Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "aaa", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "no", "question": "*Yes / No / Maybe field:: Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "17", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2025-12-19 19:47:01.708366', '2025-12-19 19:47:01.708366');
INSERT INTO public."Questions" VALUES (551, 33, 268, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-01-31 23:47:13.187997', '2026-01-31 23:47:13.187997');
INSERT INTO public."Questions" VALUES (297, 32, 228, '[{"answer": "Jeannette ", "question": "*First Name"}, {"answer": "Knop", "question": "*Last Name"}, {"answer": "7168607036", "question": "*Phone Number:: Phone"}, {"answer": "jknop5326@gmail.com", "question": "*Email:: Email"}, {"answer": "14213", "question": "*ZIP Code"}, {"answer": "Whistling", "question": "*Project Title"}, {"answer": "Improv", "question": "*CommaSemiColonList:: Discipline(s)\nEx: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Acapella ", "question": "*CommaSemiColonList:: Genre(s) Within Your Discipline(s)\nEx: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines—such as movement/theatre or music/visual art—please list the genre and sub-genre for each discipline."}, {"answer": "Whistling real fucking loud", "question": "*Proposal Description"}, {"answer": "", "question": "Work Sample Links (1–3)\nShare one to three links to examples of your work (if available)."}, {"answer": "Humans", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "None", "question": "*Equipment You Need Provided by Us\nEx: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "", "question": "Equipment You Could Provide\nExamples: sound gear, lighting gear, pop-up tent."}, {"answer": "Not much", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Yes", "question": "*Yes / No / Maybe:: Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "All", "question": "*AVAILABILITY (MAY 14–17)\nBe as specific as possible."}]', NULL, '2025-12-03 16:19:17.430656', '2025-12-03 17:34:50.974638');
INSERT INTO public."Questions" VALUES (552, 29, 268, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-01-31 23:47:13.18897', '2026-01-31 23:47:13.18897');
INSERT INTO public."Questions" VALUES (553, 33, 302, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-01-31 23:59:08.154908', '2026-01-31 23:59:08.154908');
INSERT INTO public."Questions" VALUES (554, 29, 302, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-01-31 23:59:08.155984', '2026-01-31 23:59:08.155984');
INSERT INTO public."Questions" VALUES (555, 31, 302, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-01-31 23:59:08.157062', '2026-01-31 23:59:08.157062');
INSERT INTO public."Questions" VALUES (556, 34, 302, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-01-31 23:59:08.157932', '2026-01-31 23:59:08.157932');
INSERT INTO public."Questions" VALUES (557, 35, 302, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-01-31 23:59:08.158784', '2026-01-31 23:59:08.158784');
INSERT INTO public."Questions" VALUES (341, 32, 240, '[{"answer": "David", "question": "*First Name"}, {"answer": "ADAMCZYK", "question": "*Last Name"}, {"answer": "7165786991", "question": "*Phone Number:: Phone"}, {"answer": "DGA8787@aol.com", "question": "*Email:: Email"}, {"answer": "14201", "question": "*ZIP Code"}, {"answer": "Busking", "question": "*Project Title"}, {"answer": "Music;Busking", "question": "*CommaSemiColonList:: Discipline(s)\nEx: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Eastern European;Klezmer;Roma", "question": "*CommaSemiColonList:: Genre(s) Within Your Discipline(s)\nEx: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines—such as movement/theatre or music/visual art—please list the genre and sub-genre for each discipline."}, {"answer": "Deep cuts transcribed by ear from Bucharest and beyond.", "question": "*Proposal Description"}, {"answer": "https://www.youtube.com/watch?v=ucMubRxkXCw", "question": "Work Sample Links (1–3)\nShare one to three links to examples of your work (if available)."}, {"answer": "violin, bow, violin case, sign, blanket", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "a resonant space would be nice", "question": "*Equipment You Need Provided by Us\nEx: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "help plan a festival", "question": "Equipment You Could Provide\nExamples: sound gear, lighting gear, pop-up tent."}, {"answer": "5x5", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Yes", "question": "*Yes / No / Maybe:: Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "show me what you got", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "oh ill be there", "question": "*AVAILABILITY (MAY 14–17)\nBe as specific as possible."}]', NULL, '2025-12-07 17:31:02.2985', '2025-12-07 17:31:02.2985');
INSERT INTO public."Questions" VALUES (342, 9, 240, '[{"answer": "", "question": "*Integer::How many members are involved in this proposal?"}, {"answer": "", "question": "*List all names of artists."}, {"answer": "", "question": "URL::Do you have a Facebook link? Enter it here."}, {"answer": "", "question": "URL::Do you have a personal website link? Enter it here."}, {"answer": "", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "", "question": "*Are all people participating in your event of age 21+?"}, {"answer": "", "question": "*Provide a description of your event that only you can review."}, {"answer": "", "question": "*Up to 125 chars::Provide a description of your event for the news."}, {"answer": "", "question": "*Provide a description of your event for the web."}, {"answer": "", "question": "*What does ReFringe mean to you?"}, {"answer": "", "question": "Does your work involve AI ? If so, how? (There are no disqualifying answers. This question is for organizational purposes ONLY.)"}, {"answer": "", "question": "Miscellaneous?"}]', NULL, '2025-12-07 17:32:51.589276', '2025-12-07 17:32:51.589276');
INSERT INTO public."Questions" VALUES (343, 2, 240, '[{"answer": "", "question": "I am/we are willing to play purely acoustic."}, {"answer": "", "question": "What venues has your band played? Can you give a gig history?"}, {"answer": "", "question": "Integer (1 - 10)::How loud are you?"}, {"answer": "", "question": "What groups or styles have you shared bills with?"}, {"answer": "", "question": "Instrumentation."}, {"answer": "", "question": "I/we requre a full PA."}, {"answer": "", "question": "When playing with a PA, how many vocal mics?"}, {"answer": "", "question": "I/we own a PA that others can use *and share* for shows?"}, {"answer": "", "question": "I/we can you provide amps or other equipment to share?"}, {"answer": "", "question": "I/we can provide drums to share."}, {"answer": "", "question": "I/we primarily perform as a DJ/laptopist/electronic instrumentalist."}, {"answer": "", "question": "I have DJ/mixing equipment I can share."}, {"answer": "", "question": "I or my groups'' members would be interested in improvising in larger (scheduled) collaborations?"}, {"answer": "", "question": "Miscellaneous?"}]', NULL, '2025-12-07 17:32:51.590444', '2025-12-07 17:32:51.590444');
INSERT INTO public."Questions" VALUES (344, 1, 240, '[{"answer": "", "question": "Performance duration - minimum"}, {"answer": "", "question": "Performance duration - maximum"}, {"answer": "", "question": "Setup time."}, {"answer": "", "question": "Teardown time."}, {"answer": "", "question": "What is the minimum amount of space for your performance?"}, {"answer": "", "question": "The majority of our performance is original works."}, {"answer": "", "question": "We are interested in playing outside."}, {"answer": "", "question": "We have a venue prearranged."}, {"answer": "", "question": "Our act requires seating."}, {"answer": "", "question": "I am willing to perform in non-traditional settings."}, {"answer": "", "question": "I am willing to perform at opening/closing ceremonies."}, {"answer": "", "question": "We would like to project images."}, {"answer": "", "question": "I book or otherwise organize bills/shows (not just those involving my own group)."}, {"answer": "", "question": "I''d like to help run a show."}, {"answer": "", "question": "Are you open to artists in different modalities sharing your time/space?"}, {"answer": "", "question": "Miscellaneous?"}]', NULL, '2025-12-07 17:32:51.591444', '2025-12-07 17:32:51.591444');
INSERT INTO public."Questions" VALUES (558, 7, 302, '[{"answer": "", "question": "Please list website addresses or URLs to music/art repositories."}]', NULL, '2026-01-31 23:59:55.75611', '2026-01-31 23:59:55.75611');
INSERT INTO public."Questions" VALUES (345, 28, 240, '[{"answer": "", "question": "Miscellaneous?"}]', NULL, '2025-12-07 17:32:51.592391', '2025-12-07 17:32:51.592391');
INSERT INTO public."Questions" VALUES (346, 26, 240, '[{"answer": "", "question": "Do you need a place to change clothes/costumes, i.e, a dressing room?"}, {"answer": "", "question": "Miscellaneous?"}]', NULL, '2025-12-07 17:32:51.593215', '2025-12-07 17:32:51.593215');
INSERT INTO public."Questions" VALUES (106, 9, 64, '[{"answer": "Yes<<<", "question": "*Yes / No::Are you from out of town?"}, {"answer": "20", "question": "*Integer::How many are you?"}, {"answer": "Monica, clown ", "question": "*What are your names and roles?"}, {"answer": "", "question": "URL::Do you have a Facebook link? Enter it here."}, {"answer": "", "question": "URL::Do you have a personal website link? Enter it here."}, {"answer": "", "question": "URL::Do you have an image link? Enter it here."}, {"answer": "", "question": "URL::Do you have a video link? Enter it here?"}, {"answer": "No", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "No", "question": "Any other links to show us?"}, {"answer": "Yes", "question": "Yes / No::Will your event be kid friendly?"}, {"answer": "Yes", "question": "Yes / No::Will your event contain adult content?"}, {"answer": "Yes", "question": "*Are all people participating in your event of age 21+?"}, {"answer": "Stuff", "question": "*Provide a description of your event that only you can review."}, {"answer": "Clowns", "question": "*Up to 125 chars::Provide a description of your event for the news."}, {"answer": "Clowns ", "question": "*Provide a description of your event for the web."}, {"answer": "Clowns", "question": "*What ReFringe ideals will be demonstrated by your event?"}, {"answer": "No", "question": "Notes (miscellaneous)?"}]', NULL, '2025-06-04 16:37:07.442757', '2025-12-07 20:05:08.931218');
INSERT INTO public."Questions" VALUES (107, 3, 64, '[{"answer": "Monica ", "question": "Director(s)"}, {"answer": "Monica", "question": "Cast"}, {"answer": "Frienda", "question": "Crew"}, {"answer": "Clowns", "question": "Production Co."}, {"answer": "No", "question": "If you need amplification or stereo playback, please see above (performance) questions."}, {"answer": "Stuff", "question": "List pieces and sizes."}, {"answer": "Yes", "question": "Do you have gallery space prearranged?"}, {"answer": "Yes", "question": "Is there a live performance aspect?"}, {"answer": "Maybe", "question": "Is this an installation work?"}, {"answer": "I got it ", "question": "Describe your space needs, beyond wall space."}, {"answer": "Sure", "question": "Would you be OK with other (non-visual) artists performing alongside your work?"}]', NULL, '2025-06-04 16:37:07.443454', '2025-12-07 20:05:08.93325');
INSERT INTO public."Questions" VALUES (559, 33, 240, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-02-01 00:01:28.170035', '2026-02-01 00:01:28.170035');
INSERT INTO public."Questions" VALUES (469, 32, 305, '[{"answer": "Dewey", "question": "*First Name"}, {"answer": "Richbar", "question": "*Last Name"}, {"answer": "5859690600", "question": "*Phone Number field::Phone"}, {"answer": "potusxxx420@gmail.com", "question": "*Email field::Email"}, {"answer": "14150", "question": "*Zip field::ZIP Code"}, {"answer": "Performance art", "question": "*Proposal Title"}, {"answer": "Everything mentioned above", "question": "*Delimited List field::Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "Electronic, dance, prop comedy", "question": "*Delimited List field::Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "A pop musical about a bbq", "question": "*Proposal Description"}, {"answer": "https://youtu.be/-mRClNOwpCk?si=si1mhss4pN1fppWa\n\nhttps://youtube.com/shorts/8NWl2Giyg0k?si=nurvoMIxQc-8LmAJ\n\n", "question": "Delimited List field::Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "Music, props, song and dance, laptop, sound system for vocals and music ", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "We will work within what is possible. If things such as electric and/or a sound set up can be provided, we will be able to do more. ", "question": "*Delimited List field::Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "We have a pop up tent. We have access to sound if needed but it will be dependent on electric and we can go from there", "question": "Delimited List field::Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "At music is art, we utilized a space the size of a pop up tent. I think it was a 12 by 12 space. ", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "Yes", "question": "*Yes / No / Maybe field::Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "We love collaborating on others projects through music, film, props, dance and art all around. ", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "TBA", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2026-01-27 16:43:31.207122', '2026-01-27 16:43:31.207122');
INSERT INTO public."Questions" VALUES (560, 29, 240, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-02-01 00:01:28.171021', '2026-02-01 00:01:28.171021');
INSERT INTO public."Questions" VALUES (561, 7, 240, '[{"answer": "", "question": "Please list website addresses or URLs to music/art repositories."}]', NULL, '2026-02-01 00:01:28.171896', '2026-02-01 00:01:28.171896');
INSERT INTO public."Questions" VALUES (562, 31, 240, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-02-01 00:01:28.172721', '2026-02-01 00:01:28.172721');
INSERT INTO public."Questions" VALUES (563, 34, 240, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-02-01 00:01:28.173478', '2026-02-01 00:01:28.173478');
INSERT INTO public."Questions" VALUES (564, 35, 240, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-02-01 00:01:28.174185', '2026-02-01 00:01:28.174185');
INSERT INTO public."Questions" VALUES (565, 33, 313, '[{"answer": "", "question": "Email us: info@refringe.org, or state your interest here."}]', NULL, '2026-02-01 00:03:13.783232', '2026-02-01 00:03:13.783232');
INSERT INTO public."Questions" VALUES (470, 9, 305, '[{"answer": "", "question": "*Integer field:: How many members are involved in this proposal?"}, {"answer": "", "question": "*List all names and roles of all core participants (this helps us avoid scheduling conflicts)"}, {"answer": "", "question": "URL field:: Do you have a preferred social media profile or handle to be tagged as a participant in our own promotion efforts leading up to or during the event dates? Enter them here. (note: any Refringe promotion must be considered as *amplifying*, not replacing, your own independent promotion, and is not guaranteed.)"}, {"answer": "", "question": "URL field:: Copy and paste the URL of your preferred social media account or personal homepage, to share with the public in event listings or other publishable materials."}, {"answer": "", "question": "URL field:: Do you have an image link? Enter it here."}, {"answer": "", "question": "URL field:: Do you have a video link with examples of your work? Enter it here?"}, {"answer": "", "question": "Do you have links featuring examples of your work? Enter them here."}, {"answer": "", "question": "Any other links to show us?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event be kid friendly?"}, {"answer": "", "question": "Yes / No / Maybe field:: Will your event contain adult content?"}, {"answer": "", "question": "*Yes / No / Maybe field:: Are all participants 21+?"}, {"answer": "", "question": "*Provide a description of your event that only for organizers - be detailed (surprises are for audiences, not organizers)"}, {"answer": "", "question": "*Up to 125 chars field:: Provide a short description of your proposal, fit for print."}, {"answer": "", "question": "*Provide a description of your event for the web. This may be as long as you&#039;d like."}, {"answer": "", "question": "*What does ReFringe mean to you?"}, {"answer": "", "question": "Does your work involve AI ? If so, how? (There are no disqualifying answers. This question is for organizational purposes ONLY.)"}, {"answer": "", "question": "Miscellaneous?"}, {"answer": "", "question": "List of all known or potential other proposals that you or other members are or may be involved in, whether as proposers or as core members of. (This helps us avoid unintentional double bookings)"}]', NULL, '2026-01-27 17:10:39.723492', '2026-01-27 17:10:39.723492');
INSERT INTO public."Questions" VALUES (237, 32, 0, '[{"answer": "", "question": "*First Name"}, {"answer": "", "question": "*Last Name"}, {"answer": "", "question": "*Phone Number field:: Phone"}, {"answer": "", "question": "*Email field:: Email"}, {"answer": "", "question": "*Zip field:: ZIP Code"}, {"answer": "", "question": "*Proposal Title"}, {"answer": "", "question": "*Delimited List field:: Discipline(s)\ne.g.: Music, Theatre, Visual Arts, Film/Video, Movement/Dance, Literary, Workshop, Improv, Other (list all that apply)"}, {"answer": "", "question": "*Delimited List field:: Genre(s) Within Your Discipline(s)\ne.g.: ballet or fire spinning for movement; R&amp;B or jazz for music; live painting, watercolors for visual art.\nIf you work across multiple disciplines--such as movement/theatre or music/visual art--please list the genre and sub-genre for each discipline."}, {"answer": "", "question": "*Proposal Description"}, {"answer": "", "question": "Delimited List field:: Work Sample Links (1-3)\nShare one to three links to examples of your work (if available)."}, {"answer": "", "question": "*Equipment You Use\nDescribe the equipment you use to present your work. Be specific."}, {"answer": "", "question": "*Delimited List field:: Equipment You Need Provided by Us\ne.g.: electricity, sound setup, lighting, Wi-Fi, wall space, floor space, carpet, projector, amplifier, etc."}, {"answer": "", "question": "Delimited List field:: Equipment You Could Provide\ne.g.: sound gear, lighting gear, pop-up tent."}, {"answer": "", "question": "*Space Requirements\nHow much space do you need to present your work?"}, {"answer": "", "question": "*Yes / No / Maybe field:: Open to Collaboration?\n(Answer Yes, No, or Maybe.)"}, {"answer": "", "question": "If Yes, What Collaborations Interest You?\nOnly answer this if you selected YES on the previous question."}, {"answer": "", "question": "*AVAILABILITY (MAY 14-17)\nBe as specific as possible."}]', NULL, '2025-11-23 13:02:19.630323', '2025-12-19 20:04:26.880099');


--
-- Data for Name: Roles; Type: TABLE DATA; Schema: public; Owner: wwwolamy
--

INSERT INTO public."Roles" VALUES (9, 'Venue', '2025-04-29 23:10:44.527542', '2025-04-29 23:10:44.527542');
INSERT INTO public."Roles" VALUES (10, 'NonVenueOrg', '2025-04-29 23:11:09.564556', '2025-04-29 23:11:09.564556');
INSERT INTO public."Roles" VALUES (1, 'Organizer', '2025-02-15 20:16:07.538581', '2025-04-26 14:43:23.413678');
INSERT INTO public."Roles" VALUES (2, 'Proposer', '2025-02-15 20:16:07.538581', '2025-04-26 14:43:23.413678');
INSERT INTO public."Roles" VALUES (7, 'SuperUser', '2025-04-14 22:59:00.452387', '2025-04-26 14:43:23.413678');
INSERT INTO public."Roles" VALUES (3, 'Viewer', '2025-02-15 20:16:07.538581', '2025-04-26 14:43:23.413678');
INSERT INTO public."Roles" VALUES (8, 'Volunteer', '2025-04-26 14:42:53.381236', '2025-05-02 10:55:04.011799');


--
-- Data for Name: Screenfield_validation; Type: TABLE DATA; Schema: public; Owner: wwwolamy
--

INSERT INTO public."Screenfield_validation" VALUES (2, 'Yes / No / Maybe field', '/^(yes|no|maybe|y|n|m)$/i', 'Please enter either Yes (Y) , No (N), or Maybe (M).', '2025-05-20 22:57:20.431703', '2025-12-19 19:57:27.245646');
INSERT INTO public."Screenfield_validation" VALUES (1, '*', '^.+$', 'This field is required!', '2025-05-20 22:53:00.714135', '2025-12-12 13:52:26.958095');
INSERT INTO public."Screenfield_validation" VALUES (22, 'Delimited List field', '/^(?:\s*[^,;]+?\s*)(?:\s*[,;]\s*[^,;]+?\s*)*$/', 'Please separate items with commas or semicolons.', '2025-06-25 14:41:06.557221', '2025-12-12 13:52:26.958095');
INSERT INTO public."Screenfield_validation" VALUES (20, 'Content-Rating field', '/^(?:All Ages|PG|PG-13|R|NC-17|no limit)$/i', 'Please choose one of the valid ratings: All Ages, PG, PG-13, R, NC-17, no limit.', '2025-06-25 13:48:05.01762', '2025-12-12 13:52:26.958095');
INSERT INTO public."Screenfield_validation" VALUES (9, 'Date-Time field', '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', 'Please enter a date-time in the format, yyyy-mm-dd hh:mm.', '2025-05-23 04:27:47.471183', '2025-12-12 13:52:26.958095');
INSERT INTO public."Screenfield_validation" VALUES (15, 'Email field', '/^[^\s@]+@[^\s@]+\.[^\s@]+$/', 'Please enter a valid email address', '2025-06-23 19:50:14.654805', '2025-12-12 13:52:26.958095');
INSERT INTO public."Screenfield_validation" VALUES (21, 'Genres field', '/^(?:Music|Theater|Art|Literary|Dance|Film|Workshop)(?:\s*[,;]\s*(?:Music|Theater|Art|Literary|Dance|Film|Workshop))*$/i', 'Please select one or more valid genres, separated by "," or ";": Music, Theater, Art, Literary, Dance, Film, Workshop.', '2025-06-25 13:52:01.5224', '2025-12-12 13:52:26.958095');
INSERT INTO public."Screenfield_validation" VALUES (4, 'Integer field', '/^\d+$/', 'Please enter an integer.', '2025-05-20 23:05:32.406264', '2025-12-12 13:52:26.958095');
INSERT INTO public."Screenfield_validation" VALUES (13, 'Integer (1 - 10) field', '/^(?:[1-9]|10)$/', 'Please enter an integer from 1 to 10.', '2025-06-12 14:17:34.004374', '2025-12-12 13:52:26.958095');
INSERT INTO public."Screenfield_validation" VALUES (16, 'Phone Number field', '/^\+?[\d\s\-\(\)]{7,20}$/', 'Please enter a valid phone number.', '2025-06-23 19:52:51.88893', '2025-12-12 13:52:26.958095');
INSERT INTO public."Screenfield_validation" VALUES (23, 'Tag-List field', '/^(?:#[A-Za-z0-9]+)(?:\s#[A-Za-z0-9]+)*$/i', 'Please enter one or more tags, each beginning with ‘#’, separated by spaces', '2025-06-25 14:52:28.348358', '2025-12-12 13:52:26.958095');
INSERT INTO public."Screenfield_validation" VALUES (17, 'Time field', '/^(?:[01]\d|2[0-3]):[0-5]\d$/', 'Please enter time in HH:MM (24-hr) format.', '2025-06-23 19:53:30.511707', '2025-12-12 13:52:26.958095');
INSERT INTO public."Screenfield_validation" VALUES (3, 'True / False field', '/^(true|false|t|f)$/i', 'Please enter either True (T) or False (F).', '2025-05-20 22:59:40.046848', '2025-12-12 13:52:26.958095');
INSERT INTO public."Screenfield_validation" VALUES (11, 'Up to 125 chars field', '/^.{0,125}$/', 'Please enter up to 125 characters.', '2025-05-23 04:30:42.846158', '2025-12-12 13:52:26.958095');
INSERT INTO public."Screenfield_validation" VALUES (12, 'URL field', '/^(https?:\/\/)?([a-zA-Z0-9-]+\.)+[a-zA -Z]{2,}(:\d+)?(\/[^\s]*)?$/', 'Please enter a valid URL.', '2025-05-23 04:31:57.62509', '2025-12-12 13:52:26.958095');
INSERT INTO public."Screenfield_validation" VALUES (19, 'Venue-Type field', '/^(?:bar|club|cafe|store|park|concert venue|residence|restaurant (?:eat-in|take-out)|Other)$/i', 'Please choose one of the valid venue types: Bar, Club, Cafe, Store, Park, Concert Venue, Residence, Restaurant eat-in, Restaurant take-out, or Other.', '2025-06-25 13:47:00.662291', '2025-12-12 13:52:26.958095');
INSERT INTO public."Screenfield_validation" VALUES (24, 'Zip field', '/^\d{5}(?:-\d{4})?$/', 'Please enter a valid US ZIP code', '2025-06-25 14:55:08.161144', '2025-12-12 13:52:26.958095');


--
-- Data for Name: Users; Type: TABLE DATA; Schema: public; Owner: wwwolamy
--

INSERT INTO public."Users" VALUES (52, 2, 'Old', 'MacDonald', NULL, NULL, NULL, NULL, '716-663-1310', 'MacDonald.Sr@farm.com', 'MacDonald', '$2y$10$frUTxOQmy87C5UsO4./G3OUgrcIKWuyuJ7HGhaygenW5hDjMLGKwe', '2025-04-26 14:19:56.672335', '2025-04-26 14:19:56.672335', NULL);
INSERT INTO public."Users" VALUES (28, 1, 'Rosemary', 'Erdman', '926 Hidden Valley Crt.', 'West Seneca', 'NY', '14765', '111-111-1111', 'aaa@bbbb.edu', 'rrrrrr', '$2y$10$/y9tIcfGHlqrnxrgNe0BUuJCj6kkbgc3NxPiW0YMw50wecv.H7nwi', '2025-03-27 01:19:14.439275', '2025-04-15 00:29:28.376447', '');
INSERT INTO public."Users" VALUES (54, 2, 'Roy G', 'Biv', NULL, NULL, NULL, NULL, '123-456-7890', 'Roygbiv@rainbow.org', 'Biv', '$2y$10$zvDo6vrt4Y40uM7KTxYKzePGFNXFmFljQLgbENUR2vwTMELFbnSdu', '2025-05-13 13:16:31.926865', '2025-05-13 13:16:31.926865', NULL);
INSERT INTO public."Users" VALUES (79, 2, 'Betsy', 'Brush', NULL, NULL, NULL, NULL, '716-420-6969', 'bestybrush@gmail.com', 'Brush', '$2y$10$XE7ZoZwEfPIbB2Honk5zpexw5gb1PkqS5BPvH5SvZun1tCOpqNgIS', '2025-06-04 16:34:35.820747', '2025-06-04 16:34:35.820747', NULL);
INSERT INTO public."Users" VALUES (80, 2, 'Betsy', 'Blush', NULL, NULL, NULL, NULL, '716-424-5144', 'besty_brush@gmail.com', 'Blush', '$2y$10$QrTP7D3OzFdcZs49RKADZeYI7A14Ec2SVRz0dC8sKvgExroV5wkMC', '2025-06-04 16:36:22.099538', '2025-06-04 16:36:22.099538', NULL);
INSERT INTO public."Users" VALUES (81, 2, 'Curt', 'R', NULL, NULL, NULL, NULL, '716-555-5555', 'Steelcrazybooking@gmail.com', 'R', '$2y$10$UoEVfAYxFYmRGEnhXxxnde4sntYw02DBLkJ.Tt4orwg/M.WzHF.QC', '2025-06-04 16:36:34.565575', '2025-06-04 16:36:34.565575', NULL);
INSERT INTO public."Users" VALUES (77, 1, 'Besty', 'Brush ', 'Allen', 'Buffalo', 'Ny', '14201', '716-420-6969', 'bestybrush@gmail.com', 'Betsy$0', '$2y$10$gNmJ2PkXdh40AGoUV3UqkuS2psD09PmJ9CJKFRuiSz9TbZPaDr5Py', '2025-06-04 16:30:48.918085', '2025-06-04 16:37:31.391338', '');
INSERT INTO public."Users" VALUES (78, 1, 'Axel', 'K', '208 North St', 'Buffalo', 'NEW YORK', '14201', '518-491-3633', 'bkolcow@gmail.com', 'bonecrushr', '$2y$10$1yQCB.Mb9qgDDhlpszk4oeG/0n7ctqR/3NOG5IkgsTmOhKPLCdU3i', '2025-06-04 16:32:44.461898', '2025-06-04 16:38:29.383342', '');
INSERT INTO public."Users" VALUES (82, 2, 'Axel', 'Kolcow', NULL, NULL, NULL, NULL, '518-491-3633', 'bkolcow@gmail.com', 'Kolcow', '$2y$10$utO1QlrEOHQMKh8OX2kWs.Uy4.4fUTQiWl0bX9sRB5FMuhH7h/xZi', '2025-06-04 16:39:20.198657', '2025-06-04 16:39:20.198657', NULL);
INSERT INTO public."Users" VALUES (83, 2, 'Bombus', 'Richards', NULL, NULL, NULL, NULL, '518-868-5309', 'holdone@gmail.com', 'Richards', '$2y$10$NMz57P5PK9ViDfNV7zQ63OyEI5zUYwypBt1nrplBBcwFoQwaPD7ey', '2025-06-04 17:16:41.258236', '2025-06-04 17:16:41.258236', NULL);
INSERT INTO public."Users" VALUES (84, 2, 'McKenzie ', 'Moss', NULL, NULL, NULL, NULL, '870-913-6017', 'Ingridastermoss@gmail.com', 'Moss', '$2y$10$3b32RcjcQOl5sPLqOsucCuDQ/moRrjyHzn8kb0zPC.Z2Q7hru7x1W', '2025-06-04 17:23:54.298022', '2025-06-04 17:23:54.298022', NULL);
INSERT INTO public."Users" VALUES (85, 2, 'Muppet ', 'Jenny', NULL, NULL, NULL, NULL, '870-913-6017', 'Lmnop', 'Jenny', '$2y$10$iYUSttfsqcb6mtkM5tSOveg64WQlPkp3g5s57JByLr5UMQOiuVJue', '2025-06-04 17:30:37.314836', '2025-06-04 17:30:37.314836', NULL);
INSERT INTO public."Users" VALUES (86, 2, 'Muppet', 'Jenny''s sister I guess', NULL, NULL, NULL, NULL, '816-913-2222', 'asdfkjawherflaksehfvae@gmail.com', 'Jenny''s sister I guess', '$2y$10$DL55SVGfsn9EDU0RlJBamuFR59cjlisc5fdYUZDeW5SniSxWhQlEu', '2025-06-04 17:43:32.365193', '2025-06-04 17:43:32.365193', NULL);
INSERT INTO public."Users" VALUES (89, 2, 'Scrappy', 'Testes', NULL, NULL, NULL, NULL, '123-456-7890', 'test@testing.test', 'Testes', '$2y$10$3uMMfDFcKX6vfHIfZRjyPugcn4Yd8mFnthfKT2sGHyfSYnYVBhtFe', '2025-06-05 19:12:07.442559', '2025-06-05 19:12:07.442559', NULL);
INSERT INTO public."Users" VALUES (90, 2, 'Tubby', 'Tubors', NULL, NULL, NULL, NULL, '123-456-7890', 'testes@test.com', 'Tubors', '$2y$10$sdUJnuirk4VBIH9tV51BcuihLUoA.R.bEBnKb3Nt/e3UkEFIcjJ76', '2025-06-05 20:18:05.090591', '2025-06-05 20:18:05.090591', NULL);
INSERT INTO public."Users" VALUES (153, 2, 'Avidd', 'Damczyka', NULL, NULL, NULL, NULL, '11245567890', 'DGADAMCZYK@GMAIL.COM', 'Damczyka', '$2y$10$kH0cy1rZYgez9U5jB8EQhOM0yGeA/xsmJtDy8peOx/Dc.PAlxGuPO', '2025-12-17 17:02:21.602693', '2025-12-17 17:02:21.602693', NULL);
INSERT INTO public."Users" VALUES (154, 2, 'aaa', 'bbb', NULL, NULL, NULL, NULL, '123-456-7890', '333@333.333', 'bbb', '$2y$10$35ptb7OdrTJFzIAkDHza7OiXMu4nDDjdw8PHXshJCZ55wSSuKomO6', '2025-12-19 17:44:24.220551', '2025-12-19 17:44:24.220551', NULL);
INSERT INTO public."Users" VALUES (93, 2, 'David', 'Adamczyk', NULL, NULL, NULL, NULL, '716-578-6991', 'dgadamczyk@gmail.com', 'Adamczyk', '$2y$10$kT2yzL4WWYY69gd7ytODAe6oC1THHJt5Y8R.Z2YW/Ja10VXTeuIBy', '2025-06-06 13:29:22.032625', '2025-06-06 13:29:22.032625', NULL);
INSERT INTO public."Users" VALUES (131, 2, 'David', 'ADAMCZYK', NULL, NULL, NULL, NULL, '7165786991', 'DGA8787@aol.com', 'ADAMCZYK', '$2y$10$KG3Irf0pmJSOi66AHyPEWum4.7Slk5HcQSIBO3KNPB0Iu4JbjAPfa', '2025-12-07 17:31:02.295836', '2025-12-07 17:31:02.295836', NULL);
INSERT INTO public."Users" VALUES (95, 1, 'Ayame', 'Vest', '292, Summer Street', 'Buffalo', 'NY ', '14222', '818-512-5646', 'ayame.means.iris@gmail.com', 'AyameVest', '$2y$10$rJZbOz4hn9Sp/wJNk9TZ5u48k/JkWfbbTTjvDhH/daDpUSx1IMRMW', '2025-06-18 18:19:52.994612', '2025-06-18 18:23:23.835391', '');
INSERT INTO public."Users" VALUES (94, 2, 'Erica', 'The Ice Dragon', '', 'Buffalo', 'NY', '', '716-290-0942', 'erica.wolfling@gmail.com', 'icedragon', '$2y$10$MYDzLuuG8LelMTbE3FafSuvzXYfKzHfVxdZ1kSnitdiAOVP.86ium', '2025-06-17 15:27:34.819097', '2025-06-19 20:10:00.853974', '');
INSERT INTO public."Users" VALUES (53, 2, 'Old', 'Macdonald', 'Farm street ', 'Blazedale', 'Ny', '31310', '716-663-1310', 'MacDonald.Sr@farm.com', 'OlMacD', '$2y$10$Hci3cQhnWQMYcG2h4XH8Gefd17awxGBSgcqlHrNs7zhSagNXqKmr2', '2025-04-26 16:59:57.138799', '2025-06-19 20:10:00.853974', '');
INSERT INTO public."Users" VALUES (88, 2, 'Daniel', 'Reynolds', '5435 Tally Green Dr', 'Marietta', 'GA', '30068', '716-392-6138', 'dryenoldsling@gmail.com', 'scantron', '$2y$10$QSL/emrvYebfMD9z6nvdKO2pfwD5lBao9OgsZnsEqnP/Fh49s3Niy', '2025-06-05 19:10:28.493761', '2025-06-19 20:10:00.853974', 'you can contact via my email or phone for helping ');
INSERT INTO public."Users" VALUES (96, 2, 'tom', 'carp', NULL, NULL, NULL, NULL, '111-222-3333', 'tom@carp.com', 'carp', '$2y$10$OTxqcdiuwejAEbOi1x/iz.L8vFqc0C1kwTOoiC9I6.AtFBxkVt.ma', '2025-07-16 17:43:36.724091', '2025-07-16 17:43:36.724091', NULL);
INSERT INTO public."Users" VALUES (97, 2, 'john', 'henry', NULL, NULL, NULL, NULL, '123-456-7890', 'john@henry.com', 'henry', '$2y$10$r73xmSImTI4CGALjsHTAO.KeaTaYU2sHPTk.984z7Ps70Cb7TlN3K', '2025-07-17 00:01:07.732522', '2025-07-17 00:01:07.732522', NULL);
INSERT INTO public."Users" VALUES (156, 2, 'aaa', 'bBb', NULL, NULL, NULL, NULL, '111-111-1111', 'Lagartija.tc@gmail.com', 'bBb', '$2y$10$BCm9PtWXRa6WUQCozeVTqu6P1potQK2v.J1F6i3GfPAFcXKT2OKfy', '2025-12-19 18:50:30.249963', '2025-12-19 18:50:30.249963', NULL);
INSERT INTO public."Users" VALUES (125, 2, 'Poo', 'Poop', NULL, NULL, NULL, NULL, '7165675309', 'Superbitch@gmail.com', 'Poop', '$2y$10$9sDMiNg3.U8wMLu20H7IuOs8k4rtrUrnBhfyphBP4Y2VyzeZxmRfS', '2025-12-03 17:38:08.19368', '2025-12-03 17:38:08.19368', NULL);
INSERT INTO public."Users" VALUES (127, 2, 'David', 'Ad', NULL, NULL, NULL, NULL, '1234567', 'A@b.com', 'Ad', '$2y$10$q9AYCMdgeuFZfnZNXot6JeaxTD/uz/U0oetRJr/TyUqMGWkxjh6t.', '2025-12-03 17:48:33.077991', '2025-12-03 17:48:33.077991', NULL);
INSERT INTO public."Users" VALUES (158, 2, 'aaa', 'bbBB', NULL, NULL, NULL, NULL, '111-111-1111', 'lagarTija.TC@gmail.com', 'bbBB', '$2y$10$gED1ZpkVFVwtvMX0svA3WOSqfICD/K5Ps1Cc.1UyrkqTLXwSCYvhC', '2025-12-19 19:13:28.340094', '2025-12-19 19:13:28.340094', NULL);
INSERT INTO public."Users" VALUES (171, 2, 'Rory', 'McCormack', '274 Delaware Ave ', 'Buffalo ', 'Ny', '14202', '716-750-7981', 'hhhhhr@juno.com', 'Rehhhhhr', '$2y$10$TpiwL/QzlhyPcJ5KS5K9SO6iEZMZWB9yyCmjbI4Lr0qnqbXB9AVMK', '2026-01-17 17:37:57.609532', '2026-01-17 17:37:57.609532', '');
INSERT INTO public."Users" VALUES (103, 2, 'Paul', 'Deck', '', '', '', '', '716-982-1081', 'p.d.i.am.79@gmail.com', 'Paul12321', '$2y$10$am.G1fv1zCwK0USh1I10qOvWNp4i/PG7kX.BQmm9r1r09PGHz6trG', '2025-07-23 17:27:41.317357', '2025-07-23 17:27:41.317357', '');
INSERT INTO public."Users" VALUES (104, 2, 'Tim', 'Still', '', '', '', '', '216-570-4965', 'barkingkittenrcrds@proton.me', 'brkngkttn', '$2y$10$gnCPdoPPQo7c9TsjOiXbqeLkmJnp6kVQrfiDKNMupTOUik5bTX8.a', '2025-07-27 07:19:46.223112', '2025-07-27 07:19:46.223112', '');
INSERT INTO public."Users" VALUES (76, 1, 'Angela', 'Lopez', NULL, NULL, NULL, NULL, '716-335-0010', 'angelachristinalopez@gmail.com', 'Lopez', '$2y$10$1ru1aRmREKSBwDB4UkGDU.KtYGCjOjyabdcaoXOJXdLMLq/Ck1Bde', '2025-05-21 17:47:39.19607', '2025-08-23 13:29:34.213097', NULL);
INSERT INTO public."Users" VALUES (105, 2, 'Chris', 'Mcgee', '', '', '', '', '111-111-1111', 'cmcgeeart@yahoo.com', 'cmcgee', '$2y$10$lvre42EXQG/96IXc.6p4Ou3fquvlljR4ZGDeQ8cfTxHU4dakTHFR2', '2025-10-16 14:10:06.161168', '2025-10-16 14:10:06.161168', '');
INSERT INTO public."Users" VALUES (129, 2, 'Jeannette', 'Knop', '175 14TH ST', 'Buffalo', 'NY', '14213', '716-860-7036', 'jknop5326@gmail.com', 'Fuckyou', '$2y$10$La63FLo1JhWaUZ9IwFYff.YDdJqseudlUZLGE2XuHzxOahOs7c/Ku', '2025-12-03 17:59:17.114592', '2025-12-03 17:59:17.114592', '');
INSERT INTO public."Users" VALUES (106, 2, 'Christopher', 'McGee', NULL, NULL, NULL, NULL, '716-481-8737', 'cmcgeeart@yahoo.com', 'McGee', '$2y$10$pa4O69qyrKcMFfLawssVKeR.jY8q/M4HKDc.61k2cOe3qu.3qAYtq', '2025-10-16 18:33:10.266269', '2025-10-16 18:33:10.266269', NULL);
INSERT INTO public."Users" VALUES (107, 2, 'Zahir ', 'Kijani', NULL, NULL, NULL, NULL, '716-553-1850', 'Genecist84@gmail.com', 'Kijani', '$2y$10$dswlm0wzfKj0g7Qmt0wuMut6FEwoGNiuUs/iZIQB9h12.pULuk9Sq', '2025-11-17 19:20:17.500978', '2025-11-17 19:20:17.500978', NULL);
INSERT INTO public."Users" VALUES (108, 2, 'Jake', 'Cassel', '471 mill st.', 'Buffalo', 'NY', '14221', '716-430-5800', 'jcassel@gmail.com', 'VirtualkJk', '$2y$10$uYzyZ7UZFFV7d37AWoZd/Oo0AFxEZbs4cZWX1OJP9FP2krtj1zWai', '2025-11-18 10:55:07.531666', '2025-11-18 10:55:07.531666', '
possible contributor..');
INSERT INTO public."Users" VALUES (87, 1, 'Curt', 'Rotterdam', '', '', '', '', '716-602-2464', 'steelcrazybooking@gmail.com', 'Curt1973', '$2y$10$YxO3DU1C/wuW6d/NmRM9uuNWsRkUPpfgo6yg8k.KgUXjPrse0EKEC', '2025-06-04 20:20:56.381702', '2025-11-17 20:07:56.129237', '');
INSERT INTO public."Users" VALUES (109, 2, 'Jacob', 'Jurewicz', NULL, NULL, NULL, NULL, '716-548-7097', 'jurewiczjacob26@gmail.com', 'Jurewicz', '$2y$10$/VjH6BOkWevV8thn9wrk..o5BMHvtuddMDNua1rG0Nemc4aX0yfj2', '2025-11-19 17:41:35.337064', '2025-11-19 17:41:35.337064', NULL);
INSERT INTO public."Users" VALUES (110, 2, 'David', 'adamczyk', NULL, NULL, NULL, NULL, '716-578-6991', 'dga8787@aol.com', 'adamczyk', '$2y$10$GM.u9IQKOFs.tMZq1MdWJe//9vCPTPk5KUzC3i6jzSNx2JXurnNm6', '2025-11-24 19:38:30.731347', '2025-11-24 19:38:30.731347', NULL);
INSERT INTO public."Users" VALUES (172, 2, 'Jackelyn ', 'Kupinski', '79 Wheaton Dr', 'Cheektowaga', 'NY', '14225', '716-803-2258', '8kupinski8@gmail.com', 'SPARKLES8', '$2y$10$PtDnM3ysTA.t6svYrfSsA.4vTlRG/s5MtdFUAaNDmysdjHRecpfW6', '2026-01-18 15:24:15.166626', '2026-01-18 15:24:15.166626', '');
INSERT INTO public."Users" VALUES (169, 2, 'Ddavidski', 'Aadamczyk', '', '', '', '', '123-456-7890', 'ddga8787@aol.com', 'Aadamczyk', '$2y$10$69OH/jc.9pABGZ0Issjm3ezM0I86CxqJTZVwbTH9GAAANaWuJcb6K', '2025-12-30 16:10:15.896309', '2026-01-12 23:37:24.074777', '');
INSERT INTO public."Users" VALUES (111, 2, 'Davids mom', 'Adamczyk1', NULL, NULL, NULL, NULL, '716-479-6214', 'bhadamczyk@gmail.com', 'Adamczyk1', '$2y$10$/8KdY2EeBivO2Lb0pdaeY.0o/bvLPxUcClwuoY8zuoOlx1sB4BoD2', '2025-11-26 13:26:21.018926', '2025-11-26 13:26:21.018926', NULL);
INSERT INTO public."Users" VALUES (155, 2, 'a', 'b', NULL, NULL, NULL, NULL, '111-111-1111', 'lagartija.tc@gmail.com', 'b', '$2y$10$KEadFl05Z9rC71pSwxladukmIhMGprhVbnclMDie7zlRfeY5kyG.q', '2025-12-19 17:47:29.508309', '2025-12-19 17:47:29.508309', NULL);
INSERT INTO public."Users" VALUES (124, 2, 'Jeannette ', 'Knop', NULL, NULL, NULL, NULL, '7168607036', 'jknop5326@gmail.com', 'Knop', '$2y$10$iNUagPlm6J1Jv5yikpAHNuyPN/luKcvf9ZbJBT7kdNO5LiNT0Lk0O', '2025-12-03 16:19:17.428576', '2025-12-03 16:19:17.428576', NULL);
INSERT INTO public."Users" VALUES (126, 2, 'Curtn', 'Rott', NULL, NULL, NULL, NULL, '716-602-6666', 'Drin@gmail.com', 'Rott', '$2y$10$8LmNZdfK.Fe6SugvBG9XjugaHciee3yX1JzkKJsk7Pw5selMxV0Z.', '2025-12-03 17:41:22.324723', '2025-12-03 17:41:22.324723', NULL);
INSERT INTO public."Users" VALUES (157, 2, 'aaa', 'bbB', NULL, NULL, NULL, NULL, '111-111-1111', 'laGartija.tc@gmail.com', 'bbB', '$2y$10$U/KiPosZS7rKXFx.FS2Qg.QJPWd2nrKZTw.DoMOxQbOP8Y6tb8IZq', '2025-12-19 19:09:12.850474', '2025-12-19 19:09:12.850474', NULL);
INSERT INTO public."Users" VALUES (159, 2, 'qqq', 'www', NULL, NULL, NULL, NULL, '111-111-1111', 'lagartija.tc@gmail.Com', 'www', '$2y$10$dyb9p/SnJ1Rgf7/zSOOWae0HEloZGCWi0wNMY/RO8pueVUPrzsnJe', '2025-12-19 19:43:55.47511', '2025-12-19 19:43:55.47511', NULL);
INSERT INTO public."Users" VALUES (130, 2, 'Qwerty', 'Qwerty', NULL, NULL, NULL, NULL, '1234567', 'Qwerty@qwerty.com', 'Qwerty', '$2y$10$yXAXyUVRmTVHDuUP.P/joOeSGgUho7B.e2uaF2J.HQst5bx3KYpV.', '2025-12-03 18:21:16.170388', '2025-12-03 18:21:16.170388', NULL);
INSERT INTO public."Users" VALUES (29, 7, 'David', 'Adamczyk', '', '', '', '', '716-578-6991', 'dga8787@aol.com', 'Dga8787', '$2y$10$LY/NH7pQD3sC4ZDFEn75weELKLnJ/X3BrPRxrw3NFDUkYoLjOrGAq', '2025-04-05 09:11:38.75037', '2025-12-03 18:31:37.947918', 'Changed role from Viewer to Organizer @ 7:37pm, Sat5Apr2025, TKC.

Made notes by hand too in this thing');
INSERT INTO public."Users" VALUES (161, 2, 'qQq', 'qqqq', NULL, NULL, NULL, NULL, '111-111-1111', '111@222.333', 'qqqq', '$2y$10$N6/NSpCh0VP.AWGTXAuyzeR7qN3JcoUaVue5U7mHbbBieG9Mk9VcW', '2025-12-19 19:52:28.04801', '2025-12-19 19:52:28.04801', NULL);
INSERT INTO public."Users" VALUES (134, 2, 'Nicholas', 'Vest', '292 Summer Street', 'Buffalo', 'NY', '14222', '530-867-6222', 'ecotopian7@hotmail.com', 'ApolloLyre', '$2y$10$R42vvqgaTPZcDKPf2c3Jbexn.TopvsBt3n.neKXly6Lwhv2Og.Px6', '2025-12-10 16:57:46.42586', '2025-12-10 16:57:46.42586', 'I want to be a musical performer at an event ');
INSERT INTO public."Users" VALUES (162, 2, 'qQq', 'qQQq', NULL, NULL, NULL, NULL, '111-111-1111', 'lagartiJA.tc@gmail.com', 'qQQq', '$2y$10$AUocqVS/ZSBBikYk.3znsOY2csAhGhh4zGXM6g9v7bZbWg.z9/aZO', '2025-12-19 19:54:08.113943', '2025-12-19 19:54:08.113943', NULL);
INSERT INTO public."Users" VALUES (163, 2, 'QQqq', 'QQqq', NULL, NULL, NULL, NULL, '111-111-1111', 'lAgArtija.tc@gmail.com', 'QQqq', '$2y$10$S/LbDHMcCQwb/DinjVPvMelkvZpvxPKwzWhrdxbKdO2MLAKio6C7e', '2025-12-19 19:59:38.900033', '2025-12-19 19:59:38.900033', NULL);
INSERT INTO public."Users" VALUES (164, 2, 'www', 'wgw', NULL, NULL, NULL, NULL, '111-111-1111', 'lAgArTiJa.tc@gmail.com', 'wgw', '$2y$10$32RJIkb/pt2.Bg1TMdZyweZf5kd3YeqkaLhsoh.IF3oEk3Rf9ClfG', '2025-12-19 23:39:32.673067', '2025-12-19 23:39:32.673067', NULL);
INSERT INTO public."Users" VALUES (165, 2, 'tttt', 'tttt', NULL, NULL, NULL, NULL, '111-111-1111', 'lagartija.tc@GMAIL.COM', 'tttt', '$2y$10$nFX.SSNK2YqKWjQA4.4yruXOlQiRUGyC6zq8PHAPK94I6JCJs2.52', '2025-12-19 23:48:43.454985', '2025-12-19 23:48:43.454985', NULL);
INSERT INTO public."Users" VALUES (166, 2, 'hhh', 'hhh', NULL, NULL, NULL, NULL, '123-123-1234', 'lagartija.tc@gMaIl.CoM', 'hhh', '$2y$10$Z0fEa0Ar/f.tmyQzTqscP.1urRMNvRbDq5sXXHSfSP0BbasWx6hru', '2025-12-19 23:57:05.74298', '2025-12-19 23:57:05.74298', NULL);
INSERT INTO public."Users" VALUES (167, 2, 'vvv', 'vvv', NULL, NULL, NULL, NULL, '111-111-1111', 'lagartija.tc@gmail.coM', 'vvv', '$2y$10$tbAW2/T84FeX.6GKBsagaOF6eQApKwNjNXDRuKYajWsTHlU/MLqNO', '2025-12-20 00:05:18.216078', '2025-12-20 00:05:18.216078', NULL);
INSERT INTO public."Users" VALUES (168, 2, 'ccc', 'ccc', NULL, NULL, NULL, NULL, '222-222-2222', 'lAGARtija.tc@gmail.com', 'ccc', '$2y$10$1gSCdk.RIpv7d5QDVNqJGOOwykgFtJHwU/O38IBU5q5nFwWk0IQrW', '2025-12-20 00:13:20.27231', '2025-12-20 00:13:20.27231', NULL);
INSERT INTO public."Users" VALUES (173, 2, 'Lauren', 'Herman', '189 14th Street', 'Buffalo', 'NY', '14213', '716-771-7515', 'lo00311@gmail.com', 'Lo00311', '$2y$10$LqRHMAV1s4WePbLvGJrJTeuzR6Q1lJrLCtVmQgh5UQ4ERe58coMeS', '2026-01-19 16:51:25.645501', '2026-01-19 16:51:25.645501', '');
INSERT INTO public."Users" VALUES (170, 2, 'hhh111', 'hhh111', '', '', '', '', '123-456-7890', '111@222.333', '111111', '$2y$10$EtntE9tyofkbTUACRqsyh.2gBu349T1wdZkZGAtC0nn4YWy/Tc37K', '2026-01-12 23:15:01.586444', '2026-01-13 13:45:41.975519', '');
INSERT INTO public."Users" VALUES (2, 7, 'Thomas Kendall', 'Pajaro Carpintero', '263 Ashland Ave., Apt. 6', 'Buffalo', 'NY', '14222', '716-364-8650', 'lagartija.tc@gmail.com', 'SamohtKC', '$2y$10$ozIcSoaezwfqcYdLbWSNOeiUE918coKFfYhwMHPQVdZdUX9s4r/66', '2025-02-18 21:00:24.594642', '2026-01-12 23:24:36.405261', 'I succeeded today in making role changes for the current user on a separate page from the main application. This is meant to be a hidden page, so that only certain people can change their roles.

OK, I am adding shit NOW.');
INSERT INTO public."Users" VALUES (174, 2, 'Steven', 'Steven', '', 'Buffalo, NY (US) = New York, 42n53, 78w53', '', '', '504-208-6613', 'steve@invicta.org', 'bsteveb', '$2y$10$U.au6fnCko7z1BPt3jmg8Oni84v.8Lvk3WHN/FLy49l3Rj0wC66OW', '2026-01-19 17:23:52.454236', '2026-01-19 17:23:52.454236', '');
INSERT INTO public."Users" VALUES (175, 2, 'aaa1', 'aaa2', NULL, NULL, NULL, NULL, '111-222-3333', 'lagartija_tc@gmail.com', 'aaa2', '$2y$10$W5KFAuHmQyGWCTocoWA7ROFpIKZ4fBcZniuswtEI7tnIIqZTySVsO', '2026-01-19 18:03:29.936014', '2026-01-19 18:03:29.936014', NULL);
INSERT INTO public."Users" VALUES (176, 2, 'Cyrt', 'Rotterdam', NULL, NULL, NULL, NULL, '716-123-1234', 'steelcrazybooking@gmail.com', 'Rotterdam', '$2y$10$s60KM8EEBPdr0adONU8xr.o9KJxf/gN5YujDXeWymv8oCsqvE/cmS', '2026-01-19 18:06:33.136861', '2026-01-19 18:06:33.136861', NULL);
INSERT INTO public."Users" VALUES (177, 2, 'Steev', 'boochinski', NULL, NULL, NULL, NULL, '716-430-5090', 'steev@invicta.org', 'boochinski', '$2y$10$hdw5ctQG5oBp28cJb4AV6eQ2OVSs7MZZoCqU5tRolOzGIWs64RTs6', '2026-01-19 18:11:37.868277', '2026-01-19 18:11:37.868277', NULL);
INSERT INTO public."Users" VALUES (178, 2, 'Steve', 'Testphone', NULL, NULL, NULL, NULL, '504-208-6613', 'bsb@invicta.org', 'Testphone', '$2y$10$ZbpgXB9jHIl1dFqZqldxBekqC4LF7ks72FKKRSjbqvXzOxna0xn0y', '2026-01-19 18:13:50.857341', '2026-01-19 18:13:50.857341', NULL);
INSERT INTO public."Users" VALUES (179, 2, 'Beth', 'Carr', NULL, NULL, NULL, NULL, '716-275-3871', 'Beth.carr6@gmail.com', 'Carr', '$2y$10$RZMcQUp9w3Eq9vyj/c/x5OVHD49/v.Y5S07FrLAxQQwbLt7iFtlWm', '2026-01-19 18:32:47.258548', '2026-01-19 18:32:47.258548', NULL);
INSERT INTO public."Users" VALUES (180, 2, 'james', 'Rust', '754 amherst st.', 'BUFFALO', 'NY', '14216-3102', '716-803-5737', 'jedwardrust@gmail.com', 'VinylJerk', '$2y$10$VEBIaUm70CoglGZKYnJbbeFQ4xWbulvcYzdV7GVUgIw/Vf4WZMqYa', '2026-01-20 12:54:18.199479', '2026-01-20 12:54:18.199479', '');
INSERT INTO public."Users" VALUES (181, 2, 'Audrey', 'Hibit', '644 Moore Ave', 'Tonawanda', 'NY', '14223', '716-479-4773', 'audreyhibit@yahoo.com', 'AudreyJune', '$2y$10$1.KNl3MjZxFzFx2W0a2uCuuureahQLmENtLQVi4UxaJqTfT/838.6', '2026-01-20 14:38:07.29991', '2026-01-20 14:38:07.29991', 'Hey guys, I''ve been a hands on contributor to your MIA Refringe World for a few years now. From installations to donating supplies, transportation needs, set up and break down, exhibiting and selling my own artwork and work of other artists to support the success of the orgs MIA presence the last two years. I don''t always have the free time to attend events but as a recently former corporate manager (I left by choice) I am handy at organization, but mostly good at flying by seat of pants making use of any tools and resources available. I support this orgs vision and hope I can help either with artwork, time, sweat, gasoline, muscle, calm but fast decision making based on changing variables, I am here to help. More specific input to come from me soon I''m sure, thank you.');
INSERT INTO public."Users" VALUES (182, 2, 'Silas', 'Rubeck', '225 Kenmore Ave', 'Tonowanda', 'New York', '14223', '716-866-5784', 'silasrubeck@gmail.com', 'SilasR', '$2y$10$27joFpdLs1mNWJszDcYg.OJxegbjbESLkFjqTDXitM52m53xNiyEy', '2026-01-21 08:53:12.274689', '2026-01-21 08:53:12.274689', '');
INSERT INTO public."Users" VALUES (183, 2, 'dAVID', 'aDAMCZYK', NULL, NULL, NULL, NULL, '7165786991', 'dGA8787@aol.com', 'aDAMCZYK', '$2y$10$EFENG9oHQy0vWukBf8RUN.LvqLVsvbPQtUUwlm.5V7BXv/5CrzE3W', '2026-01-25 19:39:48.790376', '2026-01-25 19:39:48.790376', NULL);
INSERT INTO public."Users" VALUES (184, 2, 'David', 'Wasik', '143 1/2 Allen St.', 'Buffalo', 'NY', '14201', '646-296-1788', 'thewaz@thewaz.com', 'Wazwaz', '$2y$10$/8nZJJ6Zcb06MtyhuTm8yeQMKR9n4qByw3loy07ieYrdgZJPclkn.', '2026-01-27 08:12:35.055805', '2026-01-27 08:12:35.055805', 'I would like to propose a solo percussion event to take place at my addy in Allentown 143 Allen St. Its a 3 hour performance that bookends w/solo percussion ( a program that allows for audience participation) and in the middle features a different painter on each day of the festival.');
INSERT INTO public."Users" VALUES (185, 2, 'Bud', 'Redding', '218 Lowell Rd', 'Kenmore', 'NY', '14217-1222', '716-512-1931', 'DJBud61@gmail.com', 'BudRedding', '$2y$10$CS5xC57EbPe2Gy8F6jvH5.NkZ/AjleMSnaxBrfryoBynYEHVO0Gky', '2026-01-27 09:47:10.347334', '2026-01-27 09:47:10.347334', '');
INSERT INTO public."Users" VALUES (186, 2, 'Dewey', 'Richbar', NULL, NULL, NULL, NULL, '5859690600', 'potusxxx420@gmail.com', 'Richbar', '$2y$10$ly5TwXydTs5gtUpDaC.bSuPbi0urk33jxr2OLr1bHYVebMpP5AJsC', '2026-01-27 16:43:31.204678', '2026-01-27 16:43:31.204678', NULL);
INSERT INTO public."Users" VALUES (187, 2, 'Jose', 'Rodriguez', '', '', '', '', '716-523-1219', 'jrvisualsllc@gmail.com', 'JRVisuals', '$2y$10$AVSC1XxVe2nQDuzP8fvuReW7.6zSyl.j3wkuJuGpmHvW8Cw8igPCy', '2026-01-28 06:00:13.759567', '2026-01-28 06:00:13.759567', '');
INSERT INTO public."Users" VALUES (188, 2, 'Monica', 'Morrisey', 'Richmond', 'Buffalo', 'NY', '14222', '716-424-5144', 'monica.morrisey0@gmail.com', 'monica', '$2y$10$NHrFrsegu7o6OsJ5Oplf3uL/HIgwXA2jn/kZDNA6XYymtAULKRC/y', '2026-01-28 08:37:45.095897', '2026-01-28 08:37:45.095897', '');
INSERT INTO public."Users" VALUES (189, 2, 'Jonl', 'Gilliam', '754 Elmwood Ave', 'Buffalo', 'NY', '14222', '716-551-8040', 'lemacre@gmail.com', 'Gillyjay', '$2y$10$e4I7qH2.XmCfGv6oX4ZVIeOXa3xiqstz.aGguPRYQGIKZlzCQg0CG', '2026-01-28 14:42:53.427819', '2026-01-28 14:42:53.427819', 'I’m here to lend a hand anytime , I do a lot of media photography/videography and arts so I would not mind lending a hand to help in any way I can ');
INSERT INTO public."Users" VALUES (190, 2, 'Anthony', 'Mott', '130 Colvin Ave', 'Buffalo', 'NY', '14216', '716-748-1951', 'insannisproductions@gmail.com', 'Ghusun333', '$2y$10$AMUbYN.tSnvz7CB.z8Me2uzZkQoDlwVAfhKbqHPDN4S76lkRRfulO', '2026-01-28 15:04:17.379985', '2026-01-28 15:04:17.379985', '');
INSERT INTO public."Users" VALUES (191, 2, 'Tiffany ', 'Nicely ', '48 Ripley Place ', 'Buffalo ', 'NY', '14213', '716-515-5997', 'Tiffnicely@aol.com', 'tiffnicely', '$2y$10$3QPMMZKPLi6ANc7uc/00Oey4ikjtEY5Tw216YwjCtX30DvXlAX6K.', '2026-01-28 15:09:54.525006', '2026-01-28 15:09:54.525006', '');
INSERT INTO public."Users" VALUES (192, 2, 'Rhonda Lee', '73feathers', NULL, NULL, NULL, NULL, '(716)6031448', '73feathers@gmail.com', '73feathers', '$2y$10$38kQEvzN8uGnfOOZ.DSGC.5gke5wnFFbWoF.wPH3pztNCPkyJRAbu', '2026-01-28 19:17:39.584062', '2026-01-28 19:17:39.584062', NULL);
INSERT INTO public."Users" VALUES (193, 2, 'Thomas', 'CaRpEnTeR', NULL, NULL, NULL, NULL, '111-222-3333', 'lagartija.Tc@GmAiL.CoM', 'CaRpEnTeR', '$2y$10$L4EW8.HK02SXSjEamtPLce4L9hFYk6a8INu4qF0nP1X/2/ZcbyvzW', '2026-01-29 12:47:56.680711', '2026-01-29 12:47:56.680711', NULL);
INSERT INTO public."Users" VALUES (194, 2, 'Kimm', 'Moon', '405 Goethe Street,', 'Buffalo', 'NY', '14212', '716-606-8317', 'Ornatepelt@yahoo.com', 'KimmMoon', '$2y$10$1e4pvN3TieCcrieXNEEduuqC2aevwDrAg7veSsjloF6c0DOEiZfAi', '2026-01-31 18:23:40.050631', '2026-01-31 18:23:40.050631', ' I have a proposal to send PDF or MP4 via email for an art installation idea that I have');
INSERT INTO public."Users" VALUES (195, 2, 'Thaddeus', 'Cochrane', '81 College St', 'Buffalo', 'NY', '14201', '519-496-2754', 'thaddeus@claypotrecords.com', 'clayhats', '$2y$10$1D4WaB7KGyslgXppVA6C2uxhozjHTUEAdqD7mfVHQh5lYhu.Is95.', '2026-02-01 08:53:04.42447', '2026-02-01 08:53:04.42447', '');
INSERT INTO public."Users" VALUES (196, 2, 'Steve', 'Myers', '43 Carlyle Ave', 'Buffalo', 'NY', '14220', '716-868-2769', 'musicolours@yahoo.com', 'Apocasonic', '$2y$10$bovdPa2PyTWcQa.kWwv7We7nEjdyzDh.ACPB/vPUO3ccRzwhLREMe', '2026-02-03 12:14:47.234655', '2026-02-03 12:14:47.234655', '');
INSERT INTO public."Users" VALUES (197, 2, 'Hans', 'Gruber', 'Nowhere', 'Buffalo', 'NY', '14213', '716-249-0858', 'wsprbuffalo@gmail.com', 'hansgruber', '$2y$10$NtK8Ys5va9cTz2SnOS5T8.JPXTa8M.HIp5AIz5kNlWeIlrgHYQ8GO', '2026-02-03 16:40:20.975514', '2026-02-03 16:40:20.975514', '');
INSERT INTO public."Users" VALUES (198, 2, 'Hans', 'Gruber', 'Nowhere', 'Buffalo', 'NY', '14213', '716-249-0858', 'wsprbuffalo@gmail.com', 'hsgrub', '$2y$10$SFZVCx2bqcA.//9KKJCqGOmIQCjyEOVA2ufSOwfI1vM1.jcOSk59y', '2026-02-03 16:43:30.197383', '2026-02-03 16:43:30.197383', '');
INSERT INTO public."Users" VALUES (199, 2, 'Hans', 'Gruber', NULL, NULL, NULL, NULL, '716-249-0858', 'wsprbuffalo@gmail.com', 'Gruber', '$2y$10$YXq5.DAWK9La2vg1NsXGQuDhZ8BJbNldm.XEe/rV/fA6ufGs/wnai', '2026-02-03 16:59:43.329654', '2026-02-03 16:59:43.329654', NULL);


--
-- Data for Name: Venues; Type: TABLE DATA; Schema: public; Owner: wwwolamy
--

INSERT INTO public."Venues" VALUES (27, 'Benten Music Center', '[{"answer": "Ayame Vest", "question": "Owner’s Name"}, {"answer": "292 summer street", "question": "Venue Address"}, {"answer": "7169319476", "question": "Phone Number::Venue Phone #"}, {"answer": "https://Bentenmusiccenter.squarespace.com", "question": "URL::Venue Website"}, {"answer": "Ayame", "question": "*Venue Contact Person"}, {"answer": "8185125646", "question": "*Phone Number::Venue Contact Person Phone #"}, {"answer": "N/a", "question": "*Email::Venue Contact Person Email"}, {"answer": "", "question": "Venue-Type::Enter a Venue Type: Bar, Club, Cafe, Store, Park, Concert Venue, Residence, Restaurant eat-in, Restaurant take-out."}, {"answer": "", "question": "Yes / No::Available Day 1: Thu May 14th (2026)?"}, {"answer": "", "question": "Time::Select earliest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Time::Select latest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Yes / No::Available Day 2: Fri May 15th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Time::Select latest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Yes / No::Available Day 3: Sat May 16th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Time::Select latest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Yes / No::Available Day 4: Sun May 17th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Time::Select latest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Genres::Enter one or more genres: Music, Theatre, Visual Art, Literary, Dance, Street."}, {"answer": "", "question": "Genres::Preferred Genre (primary)"}, {"answer": "", "question": "Genres::Preferred Genre (secondary)"}, {"answer": "", "question": "Up to 125 chars::Size & Type of Performance Space (Be Specific)"}, {"answer": "No", "question": "Yes / No::Wall Space?"}, {"answer": "", "question": "Size of Wall Space (Be Specific)"}, {"answer": "50", "question": "Integer::Capacity"}, {"answer": "Yes", "question": "Yes / No::Elevated Stage (or similar) Area?"}, {"answer": "No", "question": "Yes / No::Sound System?"}, {"answer": "No limit", "question": "Content-Rating::Highest Allowable Content Rating: All Ages, PG, PG-13, R, NC-17, no limit."}, {"answer": "$100 for cleaning if we don''t do it", "question": "Number::Cost for Renting House Equipment?"}, {"answer": "12+", "question": "Up to 125 chars::Age Restriction – list any particular policy regarding age of entry here"}, {"answer": "No", "question": "Yes / No::Alcohol Served?"}, {"answer": "No", "question": "Yes / No::Non-Alcoholic Beverages Served?"}, {"answer": "No", "question": "Yes / No::Food Served?"}, {"answer": "Yes", "question": "Yes / No::Venue allows outside alcohol? (BYOB)"}, {"answer": "No", "question": "Yes / No::Backline Available for Bands?"}, {"answer": "Yes", "question": "Yes / No::Are There Dressing Rooms?"}, {"answer": "", "question": "Up to 125 chars::Any Performer luxuries provided by venue? (Private backroom, bathroom, etc.)"}, {"answer": "", "question": "Yes / No::Is there Seating Available?"}, {"answer": "No", "question": "Yes / No::Is there a parking lot?"}, {"answer": "No", "question": "Yes / No::Is there a separate performer Load-In location?"}, {"answer": "No", "question": "Yes / No::Wheelchair Accessible?"}, {"answer": "N/a", "question": "Regular Business Hours"}, {"answer": "", "question": "Up to 125 chars::Venue Description for reFringe website (say as much or little as you’d like)"}, {"answer": "", "question": "Up to 125 chars::Just for volunteers and organizers to see and know, what do you hope to see from your venue’s involvement with ReFringe? (say as much or little as you’d like)"}]', '2025-07-02 16:01:16.34944', '2026-02-04 00:02:58.399529', true);
INSERT INTO public."Venues" VALUES (30, 'Days Park', '[{"answer": "City of Buffalo", "question": "Owner’s Name"}, {"answer": "Days Park", "question": "Venue Address"}, {"answer": "N/a", "question": "Phone Number::Venue Phone #"}, {"answer": "N/a", "question": "URL::Venue Website"}, {"answer": "N/a", "question": "*Venue Contact Person"}, {"answer": "N/a", "question": "*Phone Number::Venue Contact Person Phone #"}, {"answer": "N/a", "question": "*Email::Venue Contact Person Email"}, {"answer": "", "question": "Venue-Type::Enter a Venue Type: Bar, Club, Cafe, Store, Park, Concert Venue, Residence, Restaurant eat-in, Restaurant take-out."}, {"answer": "", "question": "Yes / No::Available Day 1: Thu May 14th (2026)?"}, {"answer": "", "question": "Time::Select earliest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Time::Select latest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Yes / No::Available Day 2: Fri May 15th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Time::Select latest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Yes / No::Available Day 3: Sat May 16th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Time::Select latest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Yes / No::Available Day 4: Sun May 17th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Time::Select latest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Genres::Enter one or more genres: Music, Theatre, Visual Art, Literary, Dance, Street."}, {"answer": "", "question": "Genres::Preferred Genre (primary)"}, {"answer": "", "question": "Genres::Preferred Genre (secondary)"}, {"answer": "", "question": "Up to 125 chars::Size & Type of Performance Space (Be Specific)"}, {"answer": "", "question": "Yes / No::Wall Space?"}, {"answer": "", "question": "Size of Wall Space (Be Specific)"}, {"answer": "", "question": "Integer::Capacity"}, {"answer": "", "question": "Yes / No::Elevated Stage (or similar) Area?"}, {"answer": "", "question": "Yes / No::Sound System?"}, {"answer": "", "question": "Content-Rating::Highest Allowable Content Rating: All Ages, PG, PG-13, R, NC-17, no limit."}, {"answer": "", "question": "Number::Cost for Renting House Equipment?"}, {"answer": "", "question": "Up to 125 chars::Age Restriction – list any particular policy regarding age of entry here"}, {"answer": "", "question": "Yes / No::Alcohol Served?"}, {"answer": "", "question": "Yes / No::Non-Alcoholic Beverages Served?"}, {"answer": "", "question": "Yes / No::Food Served?"}, {"answer": "", "question": "Yes / No::Venue allows outside alcohol? (BYOB)"}, {"answer": "", "question": "Yes / No::Backline Available for Bands?"}, {"answer": "", "question": "Yes / No::Are There Dressing Rooms?"}, {"answer": "", "question": "Up to 125 chars::Any Performer luxuries provided by venue? (Private backroom, bathroom, etc.)"}, {"answer": "", "question": "Yes / No::Is there Seating Available?"}, {"answer": "", "question": "Yes / No::Is there a parking lot?"}, {"answer": "", "question": "Yes / No::Is there a separate performer Load-In location?"}, {"answer": "", "question": "Yes / No::Wheelchair Accessible?"}, {"answer": "", "question": "Regular Business Hours"}, {"answer": "", "question": "Up to 125 chars::Venue Description for reFringe website (say as much or little as you’d like)"}, {"answer": "", "question": "Up to 125 chars::Just for volunteers and organizers to see and know, what do you hope to see from your venue’s involvement with ReFringe? (say as much or little as you’d like)"}]', '2025-07-02 17:02:51.490986', '2026-02-04 00:02:58.400266', true);
INSERT INTO public."Venues" VALUES (34, 'Karpeles', '[{"answer": "", "question": "Owner’s Name"}, {"answer": "220 North St., Buffalo, NY, 14201", "question": "Venue Address"}, {"answer": "7168851986", "question": "Phone Number::Venue Phone #"}, {"answer": "https://www.karpeles.com", "question": "URL::Venue Website"}, {"answer": ">Unknown<", "question": "*Venue Contact Person"}, {"answer": "7168851986", "question": "*Phone Number::Venue Contact Person Phone #"}, {"answer": "N/A", "question": "*Email::Venue Contact Person Email"}, {"answer": "Other", "question": "Venue-Type::Enter a Venue Type: Bar, Club, Cafe, Store, Park, Concert Venue, Residence, Restaurant eat-in, Restaurant take-out."}, {"answer": "", "question": "Yes / No::Available Day 1: Thu May 14th (2026)?"}, {"answer": "", "question": "Time::Select earliest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Time::Select latest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Yes / No::Available Day 2: Fri May 15th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Time::Select latest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Yes / No::Available Day 3: Sat May 16th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Time::Select latest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Yes / No::Available Day 4: Sun May 17th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Time::Select latest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Genres::Enter one or more genres: Music, Theatre, Visual Art, Literary, Dance, Street."}, {"answer": "", "question": "Genres::Preferred Genre (primary)"}, {"answer": "", "question": "Genres::Preferred Genre (secondary)"}, {"answer": "", "question": "Up to 125 chars::Size & Type of Performance Space (Be Specific)"}, {"answer": "", "question": "Yes / No::Wall Space?"}, {"answer": "", "question": "Size of Wall Space (Be Specific)"}, {"answer": "", "question": "Integer::Capacity"}, {"answer": "", "question": "Yes / No::Elevated Stage (or similar) Area?"}, {"answer": "", "question": "Yes / No::Sound System?"}, {"answer": "", "question": "Content-Rating::Highest Allowable Content Rating: All Ages, PG, PG-13, R, NC-17, no limit."}, {"answer": "", "question": "Number::Cost for Renting House Equipment?"}, {"answer": "", "question": "Up to 125 chars::Age Restriction – list any particular policy regarding age of entry here"}, {"answer": "", "question": "Yes / No::Alcohol Served?"}, {"answer": "", "question": "Yes / No::Non-Alcoholic Beverages Served?"}, {"answer": "", "question": "Yes / No::Food Served?"}, {"answer": "", "question": "Yes / No::Venue allows outside alcohol? (BYOB)"}, {"answer": "", "question": "Yes / No::Backline Available for Bands?"}, {"answer": "", "question": "Yes / No::Are There Dressing Rooms?"}, {"answer": "", "question": "Up to 125 chars::Any Performer luxuries provided by venue? (Private backroom, bathroom, etc.)"}, {"answer": "", "question": "Yes / No::Is there Seating Available?"}, {"answer": "", "question": "Yes / No::Is there a parking lot?"}, {"answer": "", "question": "Yes / No::Is there a separate performer Load-In location?"}, {"answer": "", "question": "Yes / No::Wheelchair Accessible?"}, {"answer": "", "question": "Regular Business Hours"}, {"answer": "", "question": "Up to 125 chars::Venue Description for reFringe website (say as much or little as you’d like)"}, {"answer": "", "question": "Up to 125 chars::Just for volunteers and organizers to see and know, what do you hope to see from your venue’s involvement with ReFringe? (say as much or little as you’d like)"}]', '2025-07-30 18:01:11.296195', '2026-02-04 00:02:58.402125', false);
INSERT INTO public."Venues" VALUES (39, 'The Intersection Cafe', '[{"answer": "Beka", "question": "Owner’s Name"}, {"answer": "100 Elmwood Ave. ", "question": "Venue Address"}, {"answer": "", "question": "Phone Number::Venue Phone #"}, {"answer": "", "question": "URL::Venue Website"}, {"answer": "David Adamczyk", "question": "*Venue Contact Person"}, {"answer": "7165786991", "question": "*Phone Number::Venue referred by Phone #"}, {"answer": "dga8787@aol.com", "question": "Email::Main Contact Person Email"}, {"answer": "Cafe", "question": "Venue-Type::Enter a Venue Type: Bar, Club, Cafe, Store, Park, Concert Venue, Residence, Restaurant eat-in, Restaurant take-out."}, {"answer": "Yes", "question": "Yes / No::Available Day 1: Thu May 14th (2026)?"}, {"answer": "07:00", "question": "Time::Select earliest time available on Day 1: Thu May 14th"}, {"answer": "17:00", "question": "Time::Select latest time available on Day 1: Thu May 14th"}, {"answer": "Yes", "question": "Yes / No::Available Day 2: Fri May 15th?"}, {"answer": "07:00", "question": "Time::Select earliest time available on Day 2: Fri May 15th"}, {"answer": "17:00", "question": "Time::Select latest time available on Day 2: Fri May 15th"}, {"answer": "Yes", "question": "Yes / No::Available Day 3: Sat May 16th?"}, {"answer": "08:00", "question": "Time::Select earliest time available on Day 3: Sat May 16th"}, {"answer": "17:00", "question": "Time::Select latest time available on Day 3: Sat May 16th"}, {"answer": "Yes", "question": "Yes / No::Available Day 4: Sun May 17th?"}, {"answer": "08:00", "question": "Time::Select earliest time available on Day 4: Sun May 17th"}, {"answer": "17:00", "question": "Time::Select latest time available on Day 4: Sun May 17th"}, {"answer": "Art, solo performances for up to 5 people, solo busking or patio jam sessions", "question": "What performance types work in the room?"}, {"answer": "Dance, loud things, anything intolerant", "question": "What performance types won''t work in the room?"}, {"answer": "Limited sidewalk/patio space about 5x5 max", "question": "Up to 125 chars::Size & Type of Performance Space (Be Specific)"}, {"answer": "Yes", "question": "Yes / No::Wall Space?"}, {"answer": "Small and cozy", "question": "Size of Wall Space (Be Specific)"}, {"answer": "5", "question": "Integer::Capacity"}, {"answer": "No", "question": "Yes / No::Elevated Stage (or similar) Area?"}, {"answer": "Yes", "question": "Yes / No::Sound System?"}, {"answer": "R", "question": "Content-Rating::Highest Allowable Content Rating: All Ages, PG, PG-13, R, NC-17, no limit."}, {"answer": "", "question": "Up to 125 chars::Age Restriction – list any particular policy regarding age of entry here"}, {"answer": "No", "question": "Yes / No::Alcohol Served?"}, {"answer": "Yes", "question": "Yes / No::Non-Alcoholic Beverages Served?"}, {"answer": "No", "question": "Yes / No::Food Served?"}, {"answer": "No", "question": "Yes / No::Venue allows outside alcohol? (BYOB)"}, {"answer": "No", "question": "Yes / No::Backline Available for Bands?"}, {"answer": "No", "question": "Yes / No::Are There Dressing Rooms?"}, {"answer": "No", "question": "Up to 125 chars::Any Performer luxuries provided by venue? (Private backroom, bathroom, etc.)"}, {"answer": "Yes", "question": "Yes / No::Is there Seating Available?"}, {"answer": "", "question": "Yes / No::Is there a parking lot?"}, {"answer": "", "question": "Yes / No::Is there a separate performer Load-In location?"}, {"answer": "", "question": "Yes / No::Wheelchair Accessible?"}, {"answer": "Opens 7am weekdays 8am weekends, to 5pm", "question": "Regular Business Hours"}, {"answer": "", "question": "Up to 125 chars::Venue Description for reFringe website (say as much or little as you’d like)"}, {"answer": "", "question": "Up to 125 chars::Just for volunteers and organizers to see and know, what do you hope to see from your venue’s involvement with ReFringe? (say as much or little as you’d like)"}]', '2025-07-30 19:12:19.998059', '2026-02-04 00:02:58.403892', false);
INSERT INTO public."Venues" VALUES (33, 'Trinity Farms ', '[{"answer": "Rhonda Riley ", "question": "Owner’s Name"}, {"answer": "73 trinity place", "question": "Venue Address"}, {"answer": "7166031448", "question": "Phone Number::Venue Phone #"}, {"answer": "N/a", "question": "URL::Venue Website"}, {"answer": "Rhonda Riley", "question": "*Venue Contact Person"}, {"answer": "7166031448", "question": "*Phone Number::Venue Contact Person Phone #"}, {"answer": "73feathers@gmail.com", "question": "*Email::Venue Contact Person Email"}, {"answer": "Residence", "question": "Venue-Type::Enter a Venue Type: Bar, Club, Cafe, Store, Park, Concert Venue, Residence, Restaurant eat-in, Restaurant take-out."}, {"answer": "", "question": "Yes / No::Available Day 1: Thu May 14th (2026)?"}, {"answer": "", "question": "Time::Select earliest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Time::Select latest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Yes / No::Available Day 2: Fri May 15th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Time::Select latest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Yes / No::Available Day 3: Sat May 16th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Time::Select latest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Yes / No::Available Day 4: Sun May 17th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Time::Select latest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Genres::Enter one or more genres: Music, Theatre, Visual Art, Literary, Dance, Street."}, {"answer": "", "question": "Genres::Preferred Genre (primary)"}, {"answer": "", "question": "Genres::Preferred Genre (secondary)"}, {"answer": "", "question": "Up to 125 chars::Size & Type of Performance Space (Be Specific)"}, {"answer": "No", "question": "Yes / No::Wall Space?"}, {"answer": "", "question": "Size of Wall Space (Be Specific)"}, {"answer": "75", "question": "Integer::Capacity"}, {"answer": "No", "question": "Yes / No::Elevated Stage (or similar) Area?"}, {"answer": "No", "question": "Yes / No::Sound System?"}, {"answer": "", "question": "Content-Rating::Highest Allowable Content Rating: All Ages, PG, PG-13, R, NC-17, no limit."}, {"answer": "0", "question": "Number::Cost for Renting House Equipment?"}, {"answer": "No", "question": "Up to 125 chars::Age Restriction – list any particular policy regarding age of entry here"}, {"answer": "No", "question": "Yes / No::Alcohol Served?"}, {"answer": "No", "question": "Yes / No::Non-Alcoholic Beverages Served?"}, {"answer": "No", "question": "Yes / No::Food Served?"}, {"answer": "Yes", "question": "Yes / No::Venue allows outside alcohol? (BYOB)"}, {"answer": "No", "question": "Yes / No::Backline Available for Bands?"}, {"answer": "No", "question": "Yes / No::Are There Dressing Rooms?"}, {"answer": "No", "question": "Up to 125 chars::Any Performer luxuries provided by venue? (Private backroom, bathroom, etc.)"}, {"answer": "No", "question": "Yes / No::Is there Seating Available?"}, {"answer": "No", "question": "Yes / No::Is there a parking lot?"}, {"answer": "No", "question": "Yes / No::Is there a separate performer Load-In location?"}, {"answer": "Yes", "question": "Yes / No::Wheelchair Accessible?"}, {"answer": "", "question": "Regular Business Hours"}, {"answer": "", "question": "Up to 125 chars::Venue Description for reFringe website (say as much or little as you’d like)"}, {"answer": "", "question": "Up to 125 chars::Just for volunteers and organizers to see and know, what do you hope to see from your venue’s involvement with ReFringe? (say as much or little as you’d like)"}]', '2025-07-02 17:46:46.704746', '2026-02-04 00:02:58.40454', false);
INSERT INTO public."Venues" VALUES (28, 'Klienhans Music Hall', '[{"answer": "City of buffalo", "question": "Owner’s Name"}, {"answer": "3 symphony circle", "question": "Venue Address"}, {"answer": "", "question": "Phone Number::Venue Phone #"}, {"answer": "", "question": "URL::Venue Website"}, {"answer": "Tba", "question": "*Venue Contact Person"}, {"answer": "7165555555", "question": "*Phone Number::Venue Contact Person Phone #"}, {"answer": "N/a", "question": "*Email::Venue Contact Person Email"}, {"answer": "", "question": "Venue-Type::Enter a Venue Type: Bar, Club, Cafe, Store, Park, Concert Venue, Residence, Restaurant eat-in, Restaurant take-out."}, {"answer": "", "question": "Yes / No::Available Day 1: Thu May 14th (2026)?"}, {"answer": "", "question": "Time::Select earliest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Time::Select latest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Yes / No::Available Day 2: Fri May 15th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Time::Select latest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Yes / No::Available Day 3: Sat May 16th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Time::Select latest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Yes / No::Available Day 4: Sun May 17th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Time::Select latest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Genres::Enter one or more genres: Music, Theatre, Visual Art, Literary, Dance, Street."}, {"answer": "", "question": "Genres::Preferred Genre (primary)"}, {"answer": "", "question": "Genres::Preferred Genre (secondary)"}, {"answer": "", "question": "Up to 125 chars::Size & Type of Performance Space (Be Specific)"}, {"answer": "", "question": "Yes / No::Wall Space?"}, {"answer": "", "question": "Size of Wall Space (Be Specific)"}, {"answer": "", "question": "Integer::Capacity"}, {"answer": "", "question": "Yes / No::Elevated Stage (or similar) Area?"}, {"answer": "", "question": "Yes / No::Sound System?"}, {"answer": "", "question": "Content-Rating::Highest Allowable Content Rating: All Ages, PG, PG-13, R, NC-17, no limit."}, {"answer": "", "question": "Number::Cost for Renting House Equipment?"}, {"answer": "", "question": "Up to 125 chars::Age Restriction – list any particular policy regarding age of entry here"}, {"answer": "", "question": "Yes / No::Alcohol Served?"}, {"answer": "", "question": "Yes / No::Non-Alcoholic Beverages Served?"}, {"answer": "", "question": "Yes / No::Food Served?"}, {"answer": "", "question": "Yes / No::Venue allows outside alcohol? (BYOB)"}, {"answer": "", "question": "Yes / No::Backline Available for Bands?"}, {"answer": "", "question": "Yes / No::Are There Dressing Rooms?"}, {"answer": "", "question": "Up to 125 chars::Any Performer luxuries provided by venue? (Private backroom, bathroom, etc.)"}, {"answer": "", "question": "Yes / No::Is there Seating Available?"}, {"answer": "", "question": "Yes / No::Is there a parking lot?"}, {"answer": "", "question": "Yes / No::Is there a separate performer Load-In location?"}, {"answer": "", "question": "Yes / No::Wheelchair Accessible?"}, {"answer": "", "question": "Regular Business Hours"}, {"answer": "", "question": "Up to 125 chars::Venue Description for reFringe website (say as much or little as you’d like)"}, {"answer": "", "question": "Up to 125 chars::Just for volunteers and organizers to see and know, what do you hope to see from your venue’s involvement with ReFringe? (say as much or little as you’d like)"}]', '2025-07-02 16:40:51.742465', '2026-02-04 00:02:58.402728', false);
INSERT INTO public."Venues" VALUES (38, 'Art Under The Stars', '[{"answer": "Curt Rotterdam", "question": "Owner’s Name"}, {"answer": "62 college street", "question": "Venue Address"}, {"answer": "", "question": "Phone Number::Venue Phone #"}, {"answer": "", "question": "URL::Venue Website"}, {"answer": "Curt Rotterdam", "question": "*Venue Contact Person"}, {"answer": "7166022464", "question": "*Phone Number::Venue referred by Phone #"}, {"answer": "steelcrazybooking@gmail.com", "question": "Email::Main Contact Person Email"}, {"answer": "Residence", "question": "Venue-Type::Enter a Venue Type: Bar, Club, Cafe, Store, Park, Concert Venue, Residence, Restaurant eat-in, Restaurant take-out."}, {"answer": "", "question": "Yes / No::Available Day 1: Thu May 14th (2026)?"}, {"answer": "", "question": "Time::Select earliest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Time::Select latest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Yes / No::Available Day 2: Fri May 15th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Time::Select latest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Yes / No::Available Day 3: Sat May 16th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Time::Select latest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Yes / No::Available Day 4: Sun May 17th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Time::Select latest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "What performance types work in the room?"}, {"answer": "", "question": "What performance types won''t work in the room?"}, {"answer": "", "question": "Up to 125 chars::Size & Type of Performance Space (Be Specific)"}, {"answer": "", "question": "Yes / No::Wall Space?"}, {"answer": "", "question": "Size of Wall Space (Be Specific)"}, {"answer": "", "question": "Integer::Capacity"}, {"answer": "", "question": "Yes / No::Elevated Stage (or similar) Area?"}, {"answer": "", "question": "Yes / No::Sound System?"}, {"answer": "", "question": "Content-Rating::Highest Allowable Content Rating: All Ages, PG, PG-13, R, NC-17, no limit."}, {"answer": "", "question": "Up to 125 chars::Age Restriction – list any particular policy regarding age of entry here"}, {"answer": "", "question": "Yes / No::Alcohol Served?"}, {"answer": "", "question": "Yes / No::Non-Alcoholic Beverages Served?"}, {"answer": "", "question": "Yes / No::Food Served?"}, {"answer": "", "question": "Yes / No::Venue allows outside alcohol? (BYOB)"}, {"answer": "", "question": "Yes / No::Backline Available for Bands?"}, {"answer": "", "question": "Yes / No::Are There Dressing Rooms?"}, {"answer": "", "question": "Up to 125 chars::Any Performer luxuries provided by venue? (Private backroom, bathroom, etc.)"}, {"answer": "", "question": "Yes / No::Is there Seating Available?"}, {"answer": "", "question": "Yes / No::Is there a parking lot?"}, {"answer": "", "question": "Yes / No::Is there a separate performer Load-In location?"}, {"answer": "", "question": "Yes / No::Wheelchair Accessible?"}, {"answer": "", "question": "Regular Business Hours"}, {"answer": "", "question": "Up to 125 chars::Venue Description for reFringe website (say as much or little as you’d like)"}, {"answer": "", "question": "Up to 125 chars::Just for volunteers and organizers to see and know, what do you hope to see from your venue’s involvement with ReFringe? (say as much or little as you’d like)"}]', '2025-07-30 19:09:19.961599', '2026-02-04 00:02:58.398197', false);
INSERT INTO public."Venues" VALUES (37, 'Holley Farms Parking Lot', '[{"answer": "Ali(?)", "question": "Owner’s Name"}, {"answer": "233 Allen St., Buffalo, NY 14201", "question": "Venue Address"}, {"answer": "7165510161", "question": "Phone Number::Venue Phone #"}, {"answer": "", "question": "URL::Venue Website"}, {"answer": "David", "question": "*Venue Contact Person"}, {"answer": "7165786991", "question": "*Phone Number::Venue referred by Phone #"}, {"answer": "dga8787@aol.com", "question": "Email::Main Contact Person Email"}, {"answer": "", "question": "Venue-Type::Enter a Venue Type: Bar, Club, Cafe, Store, Park, Concert Venue, Residence, Restaurant eat-in, Restaurant take-out."}, {"answer": "", "question": "Yes / No::Available Day 1: Thu May 14th (2026)?"}, {"answer": "", "question": "Time::Select earliest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Time::Select latest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Yes / No::Available Day 2: Fri May 15th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Time::Select latest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Yes / No::Available Day 3: Sat May 16th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Time::Select latest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Yes / No::Available Day 4: Sun May 17th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Time::Select latest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "What performance types work in the room?"}, {"answer": "", "question": "What performance types won''t work in the room?"}, {"answer": "", "question": "Up to 125 chars::Size & Type of Performance Space (Be Specific)"}, {"answer": "", "question": "Yes / No::Wall Space?"}, {"answer": "", "question": "Size of Wall Space (Be Specific)"}, {"answer": "", "question": "Integer::Capacity"}, {"answer": "", "question": "Yes / No::Elevated Stage (or similar) Area?"}, {"answer": "", "question": "Yes / No::Sound System?"}, {"answer": "", "question": "Content-Rating::Highest Allowable Content Rating: All Ages, PG, PG-13, R, NC-17, no limit."}, {"answer": "", "question": "Up to 125 chars::Age Restriction – list any particular policy regarding age of entry here"}, {"answer": "", "question": "Yes / No::Alcohol Served?"}, {"answer": "", "question": "Yes / No::Non-Alcoholic Beverages Served?"}, {"answer": "", "question": "Yes / No::Food Served?"}, {"answer": "", "question": "Yes / No::Venue allows outside alcohol? (BYOB)"}, {"answer": "", "question": "Yes / No::Backline Available for Bands?"}, {"answer": "", "question": "Yes / No::Are There Dressing Rooms?"}, {"answer": "", "question": "Up to 125 chars::Any Performer luxuries provided by venue? (Private backroom, bathroom, etc.)"}, {"answer": "", "question": "Yes / No::Is there Seating Available?"}, {"answer": "", "question": "Yes / No::Is there a parking lot?"}, {"answer": "", "question": "Yes / No::Is there a separate performer Load-In location?"}, {"answer": "", "question": "Yes / No::Wheelchair Accessible?"}, {"answer": "", "question": "Regular Business Hours"}, {"answer": "", "question": "Up to 125 chars::Venue Description for reFringe website (say as much or little as you’d like)"}, {"answer": "", "question": "Up to 125 chars::Just for volunteers and organizers to see and know, what do you hope to see from your venue’s involvement with ReFringe? (say as much or little as you’d like)"}]', '2025-07-30 19:01:03.780828', '2026-02-04 00:02:58.400914', true);
INSERT INTO public."Venues" VALUES (31, 'Allen and Wadsworth ', '[{"answer": "City", "question": "Owner’s Name"}, {"answer": "", "question": "Venue Address"}, {"answer": "", "question": "Phone Number::Venue Phone #"}, {"answer": "", "question": "URL::Venue Website"}, {"answer": "N/a", "question": "*Venue Contact Person"}, {"answer": "N/a", "question": "*Phone Number::Venue Contact Person Phone #"}, {"answer": "N/a", "question": "*Email::Venue Contact Person Email"}, {"answer": "N/a", "question": "Venue-Type::Enter a Venue Type: Bar, Club, Cafe, Store, Park, Concert Venue, Residence, Restaurant eat-in, Restaurant take-out."}, {"answer": "", "question": "Yes / No::Available Day 1: Thu May 14th (2026)?"}, {"answer": "", "question": "Time::Select earliest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Time::Select latest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Yes / No::Available Day 2: Fri May 15th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Time::Select latest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Yes / No::Available Day 3: Sat May 16th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Time::Select latest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Yes / No::Available Day 4: Sun May 17th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Time::Select latest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Genres::Enter one or more genres: Music, Theatre, Visual Art, Literary, Dance, Street."}, {"answer": "", "question": "Genres::Preferred Genre (primary)"}, {"answer": "", "question": "Genres::Preferred Genre (secondary)"}, {"answer": "", "question": "Up to 125 chars::Size & Type of Performance Space (Be Specific)"}, {"answer": "", "question": "Yes / No::Wall Space?"}, {"answer": "", "question": "Size of Wall Space (Be Specific)"}, {"answer": "", "question": "Integer::Capacity"}, {"answer": "", "question": "Yes / No::Elevated Stage (or similar) Area?"}, {"answer": "", "question": "Yes / No::Sound System?"}, {"answer": "", "question": "Content-Rating::Highest Allowable Content Rating: All Ages, PG, PG-13, R, NC-17, no limit."}, {"answer": "", "question": "Number::Cost for Renting House Equipment?"}, {"answer": "", "question": "Up to 125 chars::Age Restriction – list any particular policy regarding age of entry here"}, {"answer": "", "question": "Yes / No::Alcohol Served?"}, {"answer": "", "question": "Yes / No::Non-Alcoholic Beverages Served?"}, {"answer": "", "question": "Yes / No::Food Served?"}, {"answer": "", "question": "Yes / No::Venue allows outside alcohol? (BYOB)"}, {"answer": "", "question": "Yes / No::Backline Available for Bands?"}, {"answer": "", "question": "Yes / No::Are There Dressing Rooms?"}, {"answer": "", "question": "Up to 125 chars::Any Performer luxuries provided by venue? (Private backroom, bathroom, etc.)"}, {"answer": "", "question": "Yes / No::Is there Seating Available?"}, {"answer": "", "question": "Yes / No::Is there a parking lot?"}, {"answer": "", "question": "Yes / No::Is there a separate performer Load-In location?"}, {"answer": "", "question": "Yes / No::Wheelchair Accessible?"}, {"answer": "", "question": "Regular Business Hours"}, {"answer": "", "question": "Up to 125 chars::Venue Description for reFringe website (say as much or little as you’d like)"}, {"answer": "", "question": "Up to 125 chars::Just for volunteers and organizers to see and know, what do you hope to see from your venue’s involvement with ReFringe? (say as much or little as you’d like)"}]', '2025-07-02 17:08:38.970357', '2026-02-04 00:02:58.396451', false);
INSERT INTO public."Venues" VALUES (32, 'Bend buffalo', '[{"answer": "Linsey", "question": "Owner’s Name"}, {"answer": "260 Allen st", "question": "Venue Address"}, {"answer": "N/a", "question": "Phone Number::Venue Phone #"}, {"answer": "N/a", "question": "URL::Venue Website"}, {"answer": "N/a", "question": "*Venue Contact Person"}, {"answer": "N/a", "question": "*Phone Number::Venue Contact Person Phone #"}, {"answer": "N/a", "question": "*Email::Venue Contact Person Email"}, {"answer": "", "question": "Venue-Type::Enter a Venue Type: Bar, Club, Cafe, Store, Park, Concert Venue, Residence, Restaurant eat-in, Restaurant take-out."}, {"answer": "", "question": "Yes / No::Available Day 1: Thu May 14th (2026)?"}, {"answer": "", "question": "Time::Select earliest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Time::Select latest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Yes / No::Available Day 2: Fri May 15th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Time::Select latest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Yes / No::Available Day 3: Sat May 16th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Time::Select latest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Yes / No::Available Day 4: Sun May 17th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Time::Select latest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Genres::Enter one or more genres: Music, Theatre, Visual Art, Literary, Dance, Street."}, {"answer": "", "question": "Genres::Preferred Genre (primary)"}, {"answer": "", "question": "Genres::Preferred Genre (secondary)"}, {"answer": "", "question": "Up to 125 chars::Size & Type of Performance Space (Be Specific)"}, {"answer": "", "question": "Yes / No::Wall Space?"}, {"answer": "", "question": "Size of Wall Space (Be Specific)"}, {"answer": "", "question": "Integer::Capacity"}, {"answer": "", "question": "Yes / No::Elevated Stage (or similar) Area?"}, {"answer": "", "question": "Yes / No::Sound System?"}, {"answer": "", "question": "Content-Rating::Highest Allowable Content Rating: All Ages, PG, PG-13, R, NC-17, no limit."}, {"answer": "", "question": "Number::Cost for Renting House Equipment?"}, {"answer": "", "question": "Up to 125 chars::Age Restriction – list any particular policy regarding age of entry here"}, {"answer": "", "question": "Yes / No::Alcohol Served?"}, {"answer": "", "question": "Yes / No::Non-Alcoholic Beverages Served?"}, {"answer": "", "question": "Yes / No::Food Served?"}, {"answer": "", "question": "Yes / No::Venue allows outside alcohol? (BYOB)"}, {"answer": "", "question": "Yes / No::Backline Available for Bands?"}, {"answer": "", "question": "Yes / No::Are There Dressing Rooms?"}, {"answer": "", "question": "Up to 125 chars::Any Performer luxuries provided by venue? (Private backroom, bathroom, etc.)"}, {"answer": "", "question": "Yes / No::Is there Seating Available?"}, {"answer": "", "question": "Yes / No::Is there a parking lot?"}, {"answer": "", "question": "Yes / No::Is there a separate performer Load-In location?"}, {"answer": "", "question": "Yes / No::Wheelchair Accessible?"}, {"answer": "", "question": "Regular Business Hours"}, {"answer": "", "question": "Up to 125 chars::Venue Description for reFringe website (say as much or little as you’d like)"}, {"answer": "", "question": "Up to 125 chars::Just for volunteers and organizers to see and know, what do you hope to see from your venue’s involvement with ReFringe? (say as much or little as you’d like)"}]', '2025-07-02 17:09:47.698351', '2026-02-04 00:02:58.398845', false);
INSERT INTO public."Venues" VALUES (29, 'If Music Be', '[{"answer": "Elise Alimo", "question": "Owner’s Name"}, {"answer": "315 Pennsylvania ", "question": "Venue Address"}, {"answer": "", "question": "Phone Number::Venue Phone #"}, {"answer": "", "question": "URL::Venue Website"}, {"answer": "N/A", "question": "*Venue Contact Person"}, {"answer": "N/A", "question": "*Phone Number::Venue Contact Person Phone #"}, {"answer": "N/a", "question": "*Email::Venue Contact Person Email"}, {"answer": "", "question": "Venue-Type::Enter a Venue Type: Bar, Club, Cafe, Store, Park, Concert Venue, Residence, Restaurant eat-in, Restaurant take-out."}, {"answer": "", "question": "Yes / No::Available Day 1: Thu May 14th (2026)?"}, {"answer": "", "question": "Time::Select earliest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Time::Select latest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Yes / No::Available Day 2: Fri May 15th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Time::Select latest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Yes / No::Available Day 3: Sat May 16th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Time::Select latest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Yes / No::Available Day 4: Sun May 17th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Time::Select latest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Genres::Enter one or more genres: Music, Theatre, Visual Art, Literary, Dance, Street."}, {"answer": "", "question": "Genres::Preferred Genre (primary)"}, {"answer": "", "question": "Genres::Preferred Genre (secondary)"}, {"answer": "", "question": "Up to 125 chars::Size & Type of Performance Space (Be Specific)"}, {"answer": "", "question": "Yes / No::Wall Space?"}, {"answer": "", "question": "Size of Wall Space (Be Specific)"}, {"answer": "", "question": "Integer::Capacity"}, {"answer": "", "question": "Yes / No::Elevated Stage (or similar) Area?"}, {"answer": "", "question": "Yes / No::Sound System?"}, {"answer": "", "question": "Content-Rating::Highest Allowable Content Rating: All Ages, PG, PG-13, R, NC-17, no limit."}, {"answer": "", "question": "Number::Cost for Renting House Equipment?"}, {"answer": "", "question": "Up to 125 chars::Age Restriction – list any particular policy regarding age of entry here"}, {"answer": "", "question": "Yes / No::Alcohol Served?"}, {"answer": "", "question": "Yes / No::Non-Alcoholic Beverages Served?"}, {"answer": "", "question": "Yes / No::Food Served?"}, {"answer": "", "question": "Yes / No::Venue allows outside alcohol? (BYOB)"}, {"answer": "", "question": "Yes / No::Backline Available for Bands?"}, {"answer": "", "question": "Yes / No::Are There Dressing Rooms?"}, {"answer": "", "question": "Up to 125 chars::Any Performer luxuries provided by venue? (Private backroom, bathroom, etc.)"}, {"answer": "", "question": "Yes / No::Is there Seating Available?"}, {"answer": "", "question": "Yes / No::Is there a parking lot?"}, {"answer": "", "question": "Yes / No::Is there a separate performer Load-In location?"}, {"answer": "", "question": "Yes / No::Wheelchair Accessible?"}, {"answer": "", "question": "Regular Business Hours"}, {"answer": "", "question": "Up to 125 chars::Venue Description for reFringe website (say as much or little as you’d like)"}, {"answer": "", "question": "Up to 125 chars::Just for volunteers and organizers to see and know, what do you hope to see from your venue’s involvement with ReFringe? (say as much or little as you’d like)"}]', '2025-07-02 16:53:44.110995', '2026-02-04 00:02:58.401547', false);
INSERT INTO public."Venues" VALUES (26, 'Ol'' Wondermoth', '[{"answer": "Ed Granger", "question": "Owner’s Name"}, {"answer": "208 North Street", "question": "Venue Address"}, {"answer": "", "question": "Phone Number::Venue Phone #"}, {"answer": "", "question": "URL::Venue Website"}, {"answer": "David Adamczyk", "question": "*Venue Contact Person"}, {"answer": "(716)578-6991", "question": "*Phone Number::Venue Contact Person Phone #"}, {"answer": "dgadamczyk@gmail.com", "question": "*Email::Venue Contact Person Email"}, {"answer": "Residence", "question": "Venue-Type::Enter a Venue Type: Bar, Club, Cafe, Store, Park, Concert Venue, Residence, Restaurant eat-in, Restaurant take-out."}, {"answer": "", "question": "Yes / No::Available Day 1: Thu May 14th (2026)?"}, {"answer": "", "question": "Time::Select earliest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Time::Select latest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Yes / No::Available Day 2: Fri May 15th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Time::Select latest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Yes / No::Available Day 3: Sat May 16th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Time::Select latest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Yes / No::Available Day 4: Sun May 17th?"}, {"answer": "", "question": "Time::Select earliest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Time::Select latest time available on Day 4: Sun May 17th"}, {"answer": "Music,Theatre, Art,Literary,Dance,Street", "question": "Genres::Enter one or more genres: Music, Theatre, Visual Art, Literary, Dance, Street."}, {"answer": "", "question": "Genres::Preferred Genre (primary)"}, {"answer": "", "question": "Genres::Preferred Genre (secondary)"}, {"answer": "Front porch with lawn, theater room with projector", "question": "Up to 125 chars::Size & Type of Performance Space (Be Specific)"}, {"answer": "No", "question": "Yes / No::Wall Space?"}, {"answer": "", "question": "Size of Wall Space (Be Specific)"}, {"answer": "100", "question": "Integer::Capacity"}, {"answer": "Yes", "question": "Yes / No::Elevated Stage (or similar) Area?"}, {"answer": "No", "question": "Yes / No::Sound System?"}, {"answer": "", "question": "Content-Rating::Highest Allowable Content Rating: All Ages, PG, PG-13, R, NC-17, no limit."}, {"answer": "", "question": "Number::Cost for Renting House Equipment?"}, {"answer": "", "question": "Up to 125 chars::Age Restriction – list any particular policy regarding age of entry here"}, {"answer": "No", "question": "Yes / No::Alcohol Served?"}, {"answer": "No", "question": "Yes / No::Non-Alcoholic Beverages Served?"}, {"answer": "No", "question": "Yes / No::Food Served?"}, {"answer": "Yes", "question": "Yes / No::Venue allows outside alcohol? (BYOB)"}, {"answer": "No", "question": "Yes / No::Backline Available for Bands?"}, {"answer": "No", "question": "Yes / No::Are There Dressing Rooms?"}, {"answer": "Private backroom, bathroom", "question": "Up to 125 chars::Any Performer luxuries provided by venue? (Private backroom, bathroom, etc.)"}, {"answer": "Yes", "question": "Yes / No::Is there Seating Available?"}, {"answer": "No", "question": "Yes / No::Is there a parking lot?"}, {"answer": "", "question": "Yes / No::Is there a separate performer Load-In location?"}, {"answer": "", "question": "Yes / No::Wheelchair Accessible?"}, {"answer": "", "question": "Regular Business Hours"}, {"answer": "", "question": "Up to 125 chars::Venue Description for reFringe website (say as much or little as you’d like)"}, {"answer": "", "question": "Up to 125 chars::Just for volunteers and organizers to see and know, what do you hope to see from your venue’s involvement with ReFringe? (say as much or little as you’d like)"}]', '2025-07-01 16:04:55.497302', '2026-02-04 00:02:58.403304', false);
INSERT INTO public."Venues" VALUES (0, 'TEMPLATE', '[{"answer": "", "question": "Owner’s Name"}, {"answer": "", "question": "Venue Address"}, {"answer": "", "question": "Phone Number field:: Venue Phone #"}, {"answer": "", "question": "URL field:: Venue Website"}, {"answer": "", "question": "*Venue Contact Person"}, {"answer": "", "question": "*Phone Number field:: Venue referred by Phone #"}, {"answer": "", "question": "Email field:: Main Contact Person Email"}, {"answer": "", "question": "Venue-Type field:: Enter a Venue Type: Bar, Club, Cafe, Store, Park, Concert Venue, Residence, Restaurant eat-in, Restaurant take-out."}, {"answer": "", "question": "Yes / No / Maybe field:: Available Day 1: Thu May 14th (2026)?"}, {"answer": "", "question": "Time field:: Select earliest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Time field:: Select latest time available on Day 1: Thu May 14th"}, {"answer": "", "question": "Yes / No / Maybe field:: Available Day 2: Fri May 15th?"}, {"answer": "", "question": "Time field:: Select earliest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Time field:: Select latest time available on Day 2: Fri May 15th"}, {"answer": "", "question": "Yes / No / Maybe field:: Available Day 3: Sat May 16th?"}, {"answer": "", "question": "Time field:: Select earliest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Time field:: Select latest time available on Day 3: Sat May 16th"}, {"answer": "", "question": "Yes / No / Maybe field:: Available Day 4: Sun May 17th?"}, {"answer": "", "question": "Time field:: Select earliest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "Time field:: Select latest time available on Day 4: Sun May 17th"}, {"answer": "", "question": "What performance types work in the room?"}, {"answer": "", "question": "What performance types won&#039;t work in the room?"}, {"answer": "", "question": "Up to 125 chars field:: Size &amp; Type of Performance Space (Be Specific)"}, {"answer": "", "question": "Yes / No / Maybe field:: Wall Space?"}, {"answer": "", "question": "Size of Wall Space (Be Specific)"}, {"answer": "", "question": "Integer field:: Capacity"}, {"answer": "", "question": "Yes / No / Maybe field:: Elevated Stage (or similar) Area?"}, {"answer": "", "question": "Yes / No / Maybe field:: Sound System?"}, {"answer": "", "question": "Content-Rating field:: Highest Allowable Content Rating: All Ages, PG, PG-13, R, NC-17, no limit."}, {"answer": "", "question": "Up to 125 chars field:: Age Restriction – list any particular policy regarding age of entry here"}, {"answer": "", "question": "Yes / No / Maybe field:: Alcohol Served?"}, {"answer": "", "question": "Yes / No / Maybe field:: Non-Alcoholic Beverages Served?"}, {"answer": "", "question": "Yes / No / Maybe field:: Food Served?"}, {"answer": "", "question": "Yes / No / Maybe field:: Venue allows outside alcohol? (BYOB)"}, {"answer": "", "question": "Yes / No / Maybe field:: Backline Available for Bands?"}, {"answer": "", "question": "Yes / No / Maybe field:: Are There Dressing Rooms?"}, {"answer": "", "question": "Up to 125 chars field:: Any Performer luxuries provided by venue? (Private backroom, bathroom, etc.)"}, {"answer": "", "question": "Yes / No / Maybe field:: Is there Seating Available?"}, {"answer": "", "question": "Yes / No / Maybe field:: Is there a parking lot?"}, {"answer": "", "question": "Yes / No / Maybe field:: Is there a separate performer Load-In location?"}, {"answer": "", "question": "Yes / No / Maybe field:: Wheelchair Accessible?"}, {"answer": "", "question": "Regular Business Hours"}, {"answer": "", "question": "Up to 125 chars field:: Venue Description for reFringe website (say as much or little as you’d like)"}, {"answer": "", "question": "Up to 125 chars field:: Just for volunteers and organizers to see and know, what do you hope to see from your venue’s involvement with ReFringe? (say as much or little as you’d like)"}]', '2025-06-22 01:07:46.281695', '2026-01-07 05:29:17.564373', false);


--
-- Data for Name: sched_Calendar; Type: TABLE DATA; Schema: public; Owner: wwwolamy
--

INSERT INTO public."sched_Calendar" VALUES (2, 333, NULL, NULL, NULL, '2026-02-03 23:05:08.391157', '2026-02-03 23:46:22.821639', 0);


--
-- Data for Name: sched_Events; Type: TABLE DATA; Schema: public; Owner: wwwolamy
--

INSERT INTO public."sched_Events" VALUES (6, 73, 0, 0, '[{"Day": "1", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "2", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "3", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "4", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}]', '2025-07-16 02:11:50.017903', '2025-07-27 23:50:19.053681');
INSERT INTO public."sched_Events" VALUES (7, 66, 0, 0, '[{"Day": "1", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "2", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "3", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "4", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}]', '2025-07-16 17:10:52.681588', '2025-07-27 23:50:19.053681');
INSERT INTO public."sched_Events" VALUES (0, 0, 0, 0, '[{"Day": "1", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "2", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "3", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "4", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}]', '2025-07-07 00:31:21.384977', '2025-07-27 23:50:19.053681');
INSERT INTO public."sched_Events" VALUES (8, 94, 0, 0, '[{"Day": "1", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "2", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "3", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "4", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}]', '2025-07-17 03:17:57.344915', '2025-07-27 23:50:19.053681');
INSERT INTO public."sched_Events" VALUES (5, 64, 59, 8, '[{"Day":"1","4am-8am":false,"8am-noon":false,"noon-4pm":true,"4pm-8pm":false,"8pm-midnight":false,"midnight-4am":false},{"Day":"2","4am-8am":false,"8am-noon":true,"noon-4pm":false,"4pm-8pm":true,"8pm-midnight":false,"midnight-4am":false},{"Day":"3","4am-8am":true,"8am-noon":false,"noon-4pm":false,"4pm-8pm":false,"8pm-midnight":true,"midnight-4am":false},{"Day":"4","4am-8am":false,"8am-noon":false,"noon-4pm":false,"4pm-8pm":false,"8pm-midnight":false,"midnight-4am":true}]', '2025-07-11 01:23:24.962609', '2025-07-27 23:50:59.539923');
INSERT INTO public."sched_Events" VALUES (9, 18, 2, 5, '[{"Day":"1","4am-8am":true,"8am-noon":true,"noon-4pm":true,"4pm-8pm":false,"8pm-midnight":false,"midnight-4am":false},{"Day":"2","4am-8am":false,"8am-noon":false,"noon-4pm":false,"4pm-8pm":true,"8pm-midnight":false,"midnight-4am":false},{"Day":"3","4am-8am":false,"8am-noon":false,"noon-4pm":false,"4pm-8pm":false,"8pm-midnight":true,"midnight-4am":false},{"Day":"4","4am-8am":false,"8am-noon":false,"noon-4pm":false,"4pm-8pm":false,"8pm-midnight":false,"midnight-4am":true}]', '2025-11-30 06:32:47.125654', '2025-11-30 06:42:10.302596');
INSERT INTO public."sched_Events" VALUES (10, 279, 4, 3, '[{"Day":"1","4am-8am":false,"8am-noon":true,"noon-4pm":false,"4pm-8pm":false,"8pm-midnight":false,"midnight-4am":false},{"Day":"2","4am-8am":true,"8am-noon":false,"noon-4pm":false,"4pm-8pm":false,"8pm-midnight":true,"midnight-4am":false},{"Day":"3","4am-8am":false,"8am-noon":false,"noon-4pm":false,"4pm-8pm":false,"8pm-midnight":false,"midnight-4am":false},{"Day":"4","4am-8am":false,"8am-noon":false,"noon-4pm":true,"4pm-8pm":false,"8pm-midnight":false,"midnight-4am":false}]', '2025-12-29 12:19:49.688036', '2025-12-29 12:21:01.438303');
INSERT INTO public."sched_Events" VALUES (11, 283, 0, 0, '[{"Day": "1", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "2", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "3", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "4", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}]', '2025-12-31 02:14:41.305689', '2025-12-31 02:14:41.305689');
INSERT INTO public."sched_Events" VALUES (12, 113, 0, 0, '[{"Day": "1", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "2", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "3", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "4", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}]', '2026-01-09 22:04:11.988774', '2026-01-09 22:04:11.988774');
INSERT INTO public."sched_Events" VALUES (13, 240, 30, 2, '[{"Day":"1","4am-8am":false,"8am-noon":false,"noon-4pm":true,"4pm-8pm":false,"8pm-midnight":false,"midnight-4am":false},{"Day":"2","4am-8am":false,"8am-noon":false,"noon-4pm":false,"4pm-8pm":false,"8pm-midnight":false,"midnight-4am":false},{"Day":"3","4am-8am":false,"8am-noon":false,"noon-4pm":true,"4pm-8pm":false,"8pm-midnight":false,"midnight-4am":false},{"Day":"4","4am-8am":false,"8am-noon":false,"noon-4pm":false,"4pm-8pm":false,"8pm-midnight":false,"midnight-4am":false}]', '2026-02-02 13:15:29.360718', '2026-02-02 13:17:07.308211');
INSERT INTO public."sched_Events" VALUES (14, 315, 0, 0, '[{"Day": "1", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "2", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "3", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "4", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}]', '2026-02-03 12:34:06.136463', '2026-02-03 12:34:06.136463');
INSERT INTO public."sched_Events" VALUES (15, 317, 0, 0, '[{"Day": "1", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "2", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "3", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}, {"Day": "4", "4am-8am": false, "8am-noon": false, "noon-4pm": false, "4pm-8pm": false, "8pm-midnight": false, "midnight-4am": false}]', '2026-02-03 19:10:34.827351', '2026-02-03 19:10:34.827351');


--
-- Data for Name: sched_Events_Members; Type: TABLE DATA; Schema: public; Owner: wwwolamy
--

INSERT INTO public."sched_Events_Members" VALUES (60, 5, 15);
INSERT INTO public."sched_Events_Members" VALUES (61, 5, 14);
INSERT INTO public."sched_Events_Members" VALUES (68, 13, 15);
INSERT INTO public."sched_Events_Members" VALUES (69, 13, 13);
INSERT INTO public."sched_Events_Members" VALUES (66, 9, 16);
INSERT INTO public."sched_Events_Members" VALUES (67, 9, 17);


--
-- Data for Name: sched_Events_Resources; Type: TABLE DATA; Schema: public; Owner: wwwolamy
--

INSERT INTO public."sched_Events_Resources" VALUES (73, 5, 17);
INSERT INTO public."sched_Events_Resources" VALUES (74, 5, 15);
INSERT INTO public."sched_Events_Resources" VALUES (75, 5, 16);
INSERT INTO public."sched_Events_Resources" VALUES (79, 13, 17);
INSERT INTO public."sched_Events_Resources" VALUES (80, 13, 15);
INSERT INTO public."sched_Events_Resources" VALUES (78, 9, 18);


--
-- Data for Name: sched_Members; Type: TABLE DATA; Schema: public; Owner: wwwolamy
--

INSERT INTO public."sched_Members" VALUES (12, 64, 'Sad Clown 1', false, '2025-07-16 02:09:36.795436', '2025-07-16 02:09:36.795436');
INSERT INTO public."sched_Members" VALUES (13, 64, 'Sad Clown 2', true, '2025-07-16 02:09:56.308173', '2025-07-16 02:09:56.308173');
INSERT INTO public."sched_Members" VALUES (14, 73, 'Xavier', true, '2025-07-16 02:12:15.974956', '2025-07-16 02:12:15.974956');
INSERT INTO public."sched_Members" VALUES (15, 64, 'Carrie Till', true, '2025-07-16 23:45:09.292267', '2025-07-16 23:45:09.292267');
INSERT INTO public."sched_Members" VALUES (16, 18, '', true, '2025-11-30 06:41:08.839366', '2025-11-30 06:41:08.839366');
INSERT INTO public."sched_Members" VALUES (17, 18, 'David', true, '2025-11-30 06:41:24.387142', '2025-11-30 06:41:24.387142');


--
-- Data for Name: sched_Resources; Type: TABLE DATA; Schema: public; Owner: wwwolamy
--

INSERT INTO public."sched_Resources" VALUES (14, 64, 'Monkey Wrench 1', false, '2025-07-16 02:10:15.867687', '2025-07-16 02:10:15.867687');
INSERT INTO public."sched_Resources" VALUES (15, 64, 'Monkey Wrench 2', true, '2025-07-16 02:10:34.468572', '2025-07-16 02:10:34.468572');
INSERT INTO public."sched_Resources" VALUES (16, 73, 'Butterfly Chair', true, '2025-07-16 02:13:21.447143', '2025-07-16 02:13:21.447143');
INSERT INTO public."sched_Resources" VALUES (17, 66, 'Amplifier', true, '2025-07-16 17:11:27.921899', '2025-07-16 17:11:27.921899');
INSERT INTO public."sched_Resources" VALUES (18, 18, 'Whee', true, '2025-11-30 06:41:37.699069', '2025-11-30 06:41:37.699069');


--
-- Name: genres_id_seq; Type: SEQUENCE SET; Schema: public; Owner: wwwolamy
--

SELECT pg_catalog.setval('public.genres_id_seq', 20, true);


--
-- Name: proposal_details_id_seq; Type: SEQUENCE SET; Schema: public; Owner: wwwolamy
--

SELECT pg_catalog.setval('public.proposal_details_id_seq', 317, true);


--
-- Name: question_classes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: wwwolamy
--

SELECT pg_catalog.setval('public.question_classes_id_seq', 35, true);


--
-- Name: questions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: wwwolamy
--

SELECT pg_catalog.setval('public.questions_id_seq', 568, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: wwwolamy
--

SELECT pg_catalog.setval('public.roles_id_seq', 26, true);


--
-- Name: sched_Calendar_ID_seq; Type: SEQUENCE SET; Schema: public; Owner: wwwolamy
--

SELECT pg_catalog.setval('public."sched_Calendar_ID_seq"', 2, true);


--
-- Name: sched_Calendar_Venue_ID_seq; Type: SEQUENCE SET; Schema: public; Owner: wwwolamy
--

SELECT pg_catalog.setval('public."sched_Calendar_Venue_ID_seq"', 1, false);


--
-- Name: sched_events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: wwwolamy
--

SELECT pg_catalog.setval('public.sched_events_id_seq', 15, true);


--
-- Name: sched_events_members_id_seq; Type: SEQUENCE SET; Schema: public; Owner: wwwolamy
--

SELECT pg_catalog.setval('public.sched_events_members_id_seq', 69, true);


--
-- Name: sched_events_resources_id_seq; Type: SEQUENCE SET; Schema: public; Owner: wwwolamy
--

SELECT pg_catalog.setval('public.sched_events_resources_id_seq', 80, true);


--
-- Name: sched_members_id_seq; Type: SEQUENCE SET; Schema: public; Owner: wwwolamy
--

SELECT pg_catalog.setval('public.sched_members_id_seq', 17, true);


--
-- Name: sched_resources_id_seq; Type: SEQUENCE SET; Schema: public; Owner: wwwolamy
--

SELECT pg_catalog.setval('public.sched_resources_id_seq', 18, true);


--
-- Name: screenfield_validation_id_seq; Type: SEQUENCE SET; Schema: public; Owner: wwwolamy
--

SELECT pg_catalog.setval('public.screenfield_validation_id_seq', 24, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: wwwolamy
--

SELECT pg_catalog.setval('public.users_id_seq', 199, true);


--
-- Name: venues_id_seq; Type: SEQUENCE SET; Schema: public; Owner: wwwolamy
--

SELECT pg_catalog.setval('public.venues_id_seq', 40, true);


--
-- Name: Genres Genres_pkey; Type: CONSTRAINT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."Genres"
    ADD CONSTRAINT "Genres_pkey" PRIMARY KEY ("ID");


--
-- Name: Question_classes Mediums_pkey; Type: CONSTRAINT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."Question_classes"
    ADD CONSTRAINT "Mediums_pkey" PRIMARY KEY ("ID");


--
-- Name: Proposal_details Proposal_details_pkey; Type: CONSTRAINT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."Proposal_details"
    ADD CONSTRAINT "Proposal_details_pkey" PRIMARY KEY ("ID");


--
-- Name: Questions Questions_pkey; Type: CONSTRAINT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."Questions"
    ADD CONSTRAINT "Questions_pkey" PRIMARY KEY ("ID");


--
-- Name: Roles Roles_pkey; Type: CONSTRAINT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."Roles"
    ADD CONSTRAINT "Roles_pkey" PRIMARY KEY ("ID");


--
-- Name: Screenfield_validation Screenfield_validation_pkey; Type: CONSTRAINT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."Screenfield_validation"
    ADD CONSTRAINT "Screenfield_validation_pkey" PRIMARY KEY ("ID");


--
-- Name: Users Users_pkey; Type: CONSTRAINT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."Users"
    ADD CONSTRAINT "Users_pkey" PRIMARY KEY ("ID");


--
-- Name: Venues Venues_pkey; Type: CONSTRAINT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."Venues"
    ADD CONSTRAINT "Venues_pkey" PRIMARY KEY ("ID");


--
-- Name: sched_Calendar sched_Calendar_pkey; Type: CONSTRAINT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."sched_Calendar"
    ADD CONSTRAINT "sched_Calendar_pkey" PRIMARY KEY ("ID");


--
-- Name: sched_Events_Members sched_Events_Members_pkey; Type: CONSTRAINT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."sched_Events_Members"
    ADD CONSTRAINT "sched_Events_Members_pkey" PRIMARY KEY ("ID");


--
-- Name: sched_Events_Resources sched_Events_Resources_pkey; Type: CONSTRAINT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."sched_Events_Resources"
    ADD CONSTRAINT "sched_Events_Resources_pkey" PRIMARY KEY ("ID");


--
-- Name: sched_Events sched_Events_pkey; Type: CONSTRAINT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."sched_Events"
    ADD CONSTRAINT "sched_Events_pkey" PRIMARY KEY ("ID");


--
-- Name: sched_Members sched_Members_pkey; Type: CONSTRAINT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."sched_Members"
    ADD CONSTRAINT "sched_Members_pkey" PRIMARY KEY ("ID");


--
-- Name: sched_Resources sched_Resources_pkey; Type: CONSTRAINT; Schema: public; Owner: wwwolamy
--

ALTER TABLE ONLY public."sched_Resources"
    ADD CONSTRAINT "sched_Resources_pkey" PRIMARY KEY ("ID");


--
-- Name: users_pkey; Type: INDEX; Schema: public; Owner: wwwolamy
--

CREATE UNIQUE INDEX users_pkey ON public."Users" USING btree ("ID");


--
-- Name: proposal_questions_view_2 _RETURN; Type: RULE; Schema: public; Owner: wwwolamy
--

CREATE OR REPLACE VIEW public.proposal_questions_view_2 AS
 SELECT u."ID" AS "Users-ID",
    r.name AS "Role",
    u.first_name AS "First-Name",
    u.last_name AS "Last-Name",
    u.phone AS "Phone",
    u.email AS "Email",
    p."ID" AS "Proposal-details-ID",
    p.title AS "Title",
    p.question_classes AS "Question-classes",
    jsonb_agg(jsonb_build_object('Question-classes-ID', (jsonb_element.value ->> 'ID'::text), 'Question-class', (jsonb_element.value ->> 'name'::text), 'questions_answers', q.questions_answers) ORDER BY ROW(
        CASE
            WHEN ((jsonb_element.value ->> 'name'::text) = '(Include in ALL)'::text) THEN 0
            ELSE 1
        END, (jsonb_element.value ->> 'name'::text))) AS "Questions-and-Answers",
    p.created_date_time,
    p.updated_date_time
   FROM ((((public."Users" u
     JOIN public."Roles" r ON ((r."ID" = u."Roles_ID")))
     JOIN public."Proposal_details" p ON ((p."Users_ID" = u."ID")))
     LEFT JOIN LATERAL jsonb_array_elements(p.question_classes) jsonb_element(value) ON (true))
     LEFT JOIN public."Questions" q ON ((((jsonb_element.value ->> 'ID'::text) = (q."Question_classes_ID")::text) AND (q."Proposal_details_ID" = p."ID"))))
  WHERE (q."Proposal_details_ID" <> 0)
  GROUP BY u."ID", r.name, u.first_name, u.last_name, u.phone, u.email, p."ID", p.title, p.question_classes;


--
-- Name: Genres set_updated_date_time; Type: TRIGGER; Schema: public; Owner: wwwolamy
--

CREATE TRIGGER set_updated_date_time BEFORE UPDATE ON public."Genres" FOR EACH ROW EXECUTE FUNCTION public.update_updated_date_time_column();


--
-- Name: Question_classes set_updated_date_time; Type: TRIGGER; Schema: public; Owner: wwwolamy
--

CREATE TRIGGER set_updated_date_time BEFORE UPDATE ON public."Question_classes" FOR EACH ROW EXECUTE FUNCTION public.update_updated_date_time_column();


--
-- Name: Questions set_updated_date_time; Type: TRIGGER; Schema: public; Owner: wwwolamy
--

CREATE TRIGGER set_updated_date_time BEFORE UPDATE ON public."Questions" FOR EACH ROW EXECUTE FUNCTION public.update_updated_date_time_column();


--
-- Name: Roles set_updated_date_time; Type: TRIGGER; Schema: public; Owner: wwwolamy
--

CREATE TRIGGER set_updated_date_time BEFORE UPDATE ON public."Roles" FOR EACH ROW EXECUTE FUNCTION public.update_updated_date_time_column();


--
-- Name: Screenfield_validation set_updated_date_time; Type: TRIGGER; Schema: public; Owner: wwwolamy
--

CREATE TRIGGER set_updated_date_time BEFORE UPDATE ON public."Screenfield_validation" FOR EACH ROW EXECUTE FUNCTION public.update_updated_date_time_column();


--
-- Name: Users set_updated_date_time; Type: TRIGGER; Schema: public; Owner: wwwolamy
--

CREATE TRIGGER set_updated_date_time BEFORE UPDATE ON public."Users" FOR EACH ROW EXECUTE FUNCTION public.update_updated_date_time_column();


--
-- Name: Venues set_updated_date_time; Type: TRIGGER; Schema: public; Owner: wwwolamy
--

CREATE TRIGGER set_updated_date_time BEFORE UPDATE ON public."Venues" FOR EACH ROW EXECUTE FUNCTION public.update_updated_date_time_column();


--
-- Name: sched_Calendar set_updated_date_time; Type: TRIGGER; Schema: public; Owner: wwwolamy
--

CREATE TRIGGER set_updated_date_time BEFORE UPDATE ON public."sched_Calendar" FOR EACH ROW EXECUTE FUNCTION public.update_updated_date_time_column();


--
-- Name: sched_Events set_updated_date_time; Type: TRIGGER; Schema: public; Owner: wwwolamy
--

CREATE TRIGGER set_updated_date_time BEFORE UPDATE ON public."sched_Events" FOR EACH ROW EXECUTE FUNCTION public.update_updated_date_time_column();


--
-- Name: sched_Members set_updated_date_time; Type: TRIGGER; Schema: public; Owner: wwwolamy
--

CREATE TRIGGER set_updated_date_time BEFORE UPDATE ON public."sched_Members" FOR EACH ROW EXECUTE FUNCTION public.update_updated_date_time_column();


--
-- Name: sched_Resources set_updated_date_time; Type: TRIGGER; Schema: public; Owner: wwwolamy
--

CREATE TRIGGER set_updated_date_time BEFORE UPDATE ON public."sched_Resources" FOR EACH ROW EXECUTE FUNCTION public.update_updated_date_time_column();


--
-- Name: TABLE "Genres"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON TABLE public."Genres" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON TABLE public."Genres" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: COLUMN "Genres".name; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL(name) ON TABLE public."Genres" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL(name) ON TABLE public."Genres" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: TABLE "Proposal_details"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON TABLE public."Proposal_details" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON TABLE public."Proposal_details" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: TABLE "Question_classes"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON TABLE public."Question_classes" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON TABLE public."Question_classes" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: TABLE "Questions"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON TABLE public."Questions" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON TABLE public."Questions" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: COLUMN "Questions"."ID"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL("ID") ON TABLE public."Questions" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL("ID") ON TABLE public."Questions" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: COLUMN "Questions"."Question_classes_ID"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL("Question_classes_ID") ON TABLE public."Questions" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL("Question_classes_ID") ON TABLE public."Questions" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: COLUMN "Questions"."Proposal_details_ID"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL("Proposal_details_ID") ON TABLE public."Questions" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL("Proposal_details_ID") ON TABLE public."Questions" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: COLUMN "Questions".questions_answers; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL(questions_answers) ON TABLE public."Questions" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL(questions_answers) ON TABLE public."Questions" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: COLUMN "Questions".notes; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL(notes) ON TABLE public."Questions" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL(notes) ON TABLE public."Questions" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: SEQUENCE roles_id_seq; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON SEQUENCE public.roles_id_seq TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON SEQUENCE public.roles_id_seq TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: TABLE "Roles"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON TABLE public."Roles" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON TABLE public."Roles" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: TABLE "Screenfield_validation"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON TABLE public."Screenfield_validation" TO wwwolamy_tcarpenter;
GRANT ALL ON TABLE public."Screenfield_validation" TO wwwolamy_refringedb;


--
-- Name: SEQUENCE users_id_seq; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT SELECT,USAGE ON SEQUENCE public.users_id_seq TO wwwolamy_tcarpenter;


--
-- Name: TABLE "Users"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON TABLE public."Users" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON TABLE public."Users" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: SEQUENCE venues_id_seq; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON SEQUENCE public.venues_id_seq TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON SEQUENCE public.venues_id_seq TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: TABLE "Venues"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON TABLE public."Venues" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON TABLE public."Venues" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: COLUMN "Venues"."ID"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL("ID") ON TABLE public."Venues" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL("ID") ON TABLE public."Venues" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: COLUMN "Venues".name; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL(name) ON TABLE public."Venues" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL(name) ON TABLE public."Venues" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: COLUMN "Venues".questions_answers; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL(questions_answers) ON TABLE public."Venues" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL(questions_answers) ON TABLE public."Venues" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: COLUMN "Venues".created_date_time; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL(created_date_time) ON TABLE public."Venues" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL(created_date_time) ON TABLE public."Venues" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: COLUMN "Venues".updated_date_time; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL(updated_date_time) ON TABLE public."Venues" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL(updated_date_time) ON TABLE public."Venues" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: SEQUENCE genres_id_seq; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON SEQUENCE public.genres_id_seq TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON SEQUENCE public.genres_id_seq TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: TABLE orphan_proposal_details_view; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON TABLE public.orphan_proposal_details_view TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON TABLE public.orphan_proposal_details_view TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: TABLE orphan_questions_of_proposal_details_view; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON TABLE public.orphan_questions_of_proposal_details_view TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON TABLE public.orphan_questions_of_proposal_details_view TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: SEQUENCE proposal_details_id_seq; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON SEQUENCE public.proposal_details_id_seq TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON SEQUENCE public.proposal_details_id_seq TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: TABLE proposal_questions_view_2; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON TABLE public.proposal_questions_view_2 TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON TABLE public.proposal_questions_view_2 TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: SEQUENCE question_classes_id_seq; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON SEQUENCE public.question_classes_id_seq TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON SEQUENCE public.question_classes_id_seq TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: SEQUENCE questions_id_seq; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON SEQUENCE public.questions_id_seq TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON SEQUENCE public.questions_id_seq TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: TABLE "sched_Calendar"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON TABLE public."sched_Calendar" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON TABLE public."sched_Calendar" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: SEQUENCE sched_events_id_seq; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON SEQUENCE public.sched_events_id_seq TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON SEQUENCE public.sched_events_id_seq TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: TABLE "sched_Events"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON TABLE public."sched_Events" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON TABLE public."sched_Events" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: SEQUENCE sched_events_members_id_seq; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON SEQUENCE public.sched_events_members_id_seq TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON SEQUENCE public.sched_events_members_id_seq TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: TABLE "sched_Events_Members"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON TABLE public."sched_Events_Members" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON TABLE public."sched_Events_Members" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: SEQUENCE sched_events_resources_id_seq; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON SEQUENCE public.sched_events_resources_id_seq TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON SEQUENCE public.sched_events_resources_id_seq TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: TABLE "sched_Events_Resources"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON TABLE public."sched_Events_Resources" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON TABLE public."sched_Events_Resources" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: SEQUENCE sched_members_id_seq; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON SEQUENCE public.sched_members_id_seq TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON SEQUENCE public.sched_members_id_seq TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: TABLE "sched_Members"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON TABLE public."sched_Members" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON TABLE public."sched_Members" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: SEQUENCE sched_resources_id_seq; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON SEQUENCE public.sched_resources_id_seq TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON SEQUENCE public.sched_resources_id_seq TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: TABLE "sched_Resources"; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON TABLE public."sched_Resources" TO wwwolamy_tcarpenter WITH GRANT OPTION;
GRANT ALL ON TABLE public."sched_Resources" TO wwwolamy_refringedb WITH GRANT OPTION;


--
-- Name: SEQUENCE screenfield_validation_id_seq; Type: ACL; Schema: public; Owner: wwwolamy
--

GRANT ALL ON SEQUENCE public.screenfield_validation_id_seq TO wwwolamy_tcarpenter;
GRANT ALL ON SEQUENCE public.screenfield_validation_id_seq TO wwwolamy_refringedb;


--
-- PostgreSQL database dump complete
--

--\unrestrict eJx1WDI8Xw9djxzUh7kBcYIAbBJSvN6SO9kBjoktSccMLnsa22vOd1Dr45fmfFm

