import { useContext, useEffect, useState } from 'react';
import toast from 'react-hot-toast';
import { AuthContext } from '../components/Context';
import { Layout } from '../components/Layout';
import { Post } from '../components/Post';
import { RequestCard } from '../components/RequestCard';

export default function Home() {
  const auth = useContext(AuthContext);
  const [post, setPost] = useState('');
  const [timeline, setTimeline] = useState([]);
  const [requests, setRequests] = useState([]);

  const getTimeline = async () => {
    try {
      const res = await fetch(`http://localhost:8000/api/post/timeline/${auth.user.id}`);
      const data = await res.json();
      setTimeline(data);
    } catch (error) {
      toast.error(error.message);
    }
  };

  const getRequests = async () => {
    try {
      const res = await fetch(`http://localhost:8000/api/request/timeline/${auth.user.id}`);
      const data = await res.json();
      setRequests(data);
    } catch (error) {
      toast.error(error.message);
    }
  };

  useEffect(() => {
    if (auth.authorized) {
      getTimeline();
      getRequests();
    }
  }, [auth]);

  const sendPost = async () => {
    try {
      const res = await fetch('http://localhost:8000/api/post/send', {
        method: 'POST',
        body: JSON.stringify({
          post,
          author_id: auth.user.id,
        }),
      });
      await getTimeline();
    } catch (error) {
      toast.error(error.message);
    }

    setPost('');
  };

  return (
    <Layout>
      <div className="flex max-w-6xl min-h-screen py-4 mx-auto space-x-4">
        <div className="flex-1 space-y-4">
          <div className="w-full p-4 bg-gray-800 rounded-lg shadow-lg">
            <textarea
              value={post}
              onChange={(e) => setPost(e.target.value)}
              placeholder="Share something..."
              className="w-full p-4 text-white bg-gray-700 rounded-lg resize-none"
            />
            <div className="flex items-center justify-between pt-3">
              <div className="text-sm text-gray-400">
                <span>Character length: </span>
                <span className="text-gray-200">{post.length}</span>
              </div>
              <button
                onClick={sendPost}
                className="p-2 font-bold bg-green-500 rounded-lg hover:bg-green-600"
              >
                Share Post
              </button>
            </div>
          </div>
          <div className="space-y-4">
            {timeline.length > 0 ? (
              timeline.map((post) => <Post post={post} key={post.id} />)
            ) : (
              <div className="pt-12 text-2xl font-bold text-center text-gray-400">
                No posts to show
              </div>
            )}
          </div>
        </div>
        <div className="space-y-4 w-80">
          <div className="font-bold text-center">Requests</div>
          {requests.length > 0 ? (
            requests.map((request) => <RequestCard request={request} key={request.id} />)
          ) : (
            <div className="pt-12 text-center text-gray-400">You have no friend requests</div>
          )}
        </div>
      </div>
    </Layout>
  );
}
